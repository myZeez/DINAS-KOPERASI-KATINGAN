<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defensive Security Middleware
 *
 * Provides multi-layered protection with minimal performance impact:
 * - Rate limiting (token bucket)
 * - Header security
 * - Input validation
 * - WAF rules
 * - Brute force protection
 *
 * Performance target: < 10ms p99 latency
 */
class SecurityMiddleware
{
    /**
     * Security configuration cache
     */
    private array $config;

    /**
     * Precompiled regex patterns for performance
     */
    private array $patterns;

    public function __construct()
    {
        $this->config = $this->getSecurityConfig();
        $this->patterns = $this->compileSecurityPatterns();
    }

    /**
     * Handle an incoming request
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);

        try {
            // Kill switch - emergency bypass
            if (!$this->config['enabled']) {
                return $next($request);
            }

            // Stage 1: Fast checks (< 2ms target)
            if (!$this->fastSecurityChecks($request)) {
                return $this->blockRequest('fast_check_failed', $request);
            }

            // Stage 2: Rate limiting (< 3ms target via Redis pipeline)
            if (!$this->checkRateLimit($request)) {
                return $this->blockRequest('rate_limit_exceeded', $request);
            }

            // Stage 3: Input validation (< 3ms target)
            if (!$this->validateInput($request)) {
                return $this->blockRequest('input_validation_failed', $request);
            }

            // Stage 4: WAF checks (sampled for performance)
            if ($this->shouldRunWafCheck($request) && !$this->runWafCheck($request)) {
                return $this->blockRequest('waf_rule_triggered', $request);
            }

            // Dispatch background security tasks (async)
            $this->dispatchBackgroundChecks($request);

            $response = $next($request);

            // Apply security headers
            $this->applySecurityHeaders($response);

            // Log performance metrics
            $this->logSecurityMetrics($request, microtime(true) - $startTime);

            return $response;

        } catch (\Exception $e) {
            Log::error('Security middleware error', [
                'error' => $e->getMessage(),
                'request_id' => $request->headers->get('X-Request-ID', uniqid())
            ]);

            // Fail open for non-critical errors to avoid breaking the app
            return $next($request);
        }
    }

    /**
     * Fast security checks - headers, method, basic validation
     */
    private function fastSecurityChecks(Request $request): bool
    {
        // Check hostile user agents
        $userAgent = $request->userAgent();
        if ($userAgent && $this->isHostileUserAgent($userAgent)) {
            return false;
        }

        // Check for suspicious headers
        if ($this->hasSuspiciousHeaders($request)) {
            return false;
        }

        // Basic request size check
        if ($request->getSize() > $this->config['max_request_size']) {
            return false;
        }

        return true;
    }

    /**
     * Token bucket rate limiting with Redis pipeline
     */
    private function checkRateLimit(Request $request): bool
    {
        $ip = $request->ip();
        $route = $request->route()?->getName() ?? 'unknown';
        $user = $request->user()?->id;

        $limits = [
            "rate_limit:ip:{$ip}" => $this->config['rate_limit']['per_ip_per_minute'],
            "rate_limit:route:{$route}" => $this->config['rate_limit']['per_route_per_minute'] ?? 1000,
        ];

        if ($user) {
            $limits["rate_limit:user:{$user}"] = $this->config['rate_limit']['per_user_per_minute'];
        }

        return $this->checkTokenBucket($limits);
    }

    /**
     * Token bucket implementation with Redis pipeline
     */
    private function checkTokenBucket(array $limits): bool
    {
        $redis = Redis::connection();
        $pipeline = $redis->pipeline();

        $keys = array_keys($limits);

        // Pipeline Redis operations for performance
        foreach ($keys as $key) {
            $pipeline->incr($key);
            $pipeline->expire($key, 60); // 1 minute window
        }

        $results = $pipeline->execute();

        // Check limits
        for ($i = 0; $i < count($keys); $i++) {
            $count = $results[$i * 2]; // Every second result is the count
            $limit = $limits[$keys[$i]];

            if ($count > $limit) {
                return false;
            }
        }

        return true;
    }

    /**
     * Input validation with lightweight schema check
     */
    private function validateInput(Request $request): bool
    {
        // Skip validation for GET requests without parameters
        if ($request->isMethod('GET') && empty($request->query())) {
            return true;
        }

        // Check for basic SQL injection patterns
        $inputs = array_merge(
            $request->query(),
            $request->request->all(),
            $request->headers->all()
        );

        foreach ($inputs as $key => $value) {
            if (is_string($value) && $this->containsSqlInjection($value)) {
                return false;
            }

            if (is_string($value) && $this->containsXss($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Lightweight WAF check with sampling
     */
    private function runWafCheck(Request $request): bool
    {
        $content = $request->getContent();

        // Skip if content is too large (avoid performance hit)
        if (strlen($content) > $this->config['waf']['max_content_scan_size']) {
            return true;
        }

        // Check against precompiled patterns
        foreach ($this->patterns['waf'] as $pattern) {
            if (preg_match($pattern, $content)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if WAF check should run (sampling)
     */
    private function shouldRunWafCheck(Request $request): bool
    {
        // Always check POST/PUT/PATCH requests
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            return true;
        }

        // Sample other requests
        return rand(1, 100) <= ($this->config['waf']['sampling_rate'] * 100);
    }

    /**
     * Check for SQL injection patterns
     */
    private function containsSqlInjection(string $input): bool
    {
        foreach ($this->patterns['sqli'] as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check for XSS patterns
     */
    private function containsXss(string $input): bool
    {
        foreach ($this->patterns['xss'] as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check for hostile user agents
     */
    private function isHostileUserAgent(string $userAgent): bool
    {
        $hostilePatterns = [
            '/sqlmap/i',
            '/nikto/i',
            '/nmap/i',
            '/masscan/i',
            '/nessus/i'
        ];

        foreach ($hostilePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for suspicious headers
     */
    private function hasSuspiciousHeaders(Request $request): bool
    {
        $suspiciousHeaders = [
            'X-Forwarded-Host' => function($value) {
                return !in_array($value, config('app.allowed_hosts', []));
            },
            'Host' => function($value) {
                return !in_array($value, config('app.allowed_hosts', []));
            }
        ];

        foreach ($suspiciousHeaders as $header => $validator) {
            $value = $request->header($header);
            if ($value && $validator($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply security headers to response
     */
    private function applySecurityHeaders(Response $response): void
    {
        $headers = [
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Content-Security-Policy' => "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'",
        ];

        if (request()->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $name => $value) {
            $response->headers->set($name, $value);
        }
    }

    /**
     * Dispatch background security checks
     */
    private function dispatchBackgroundChecks(Request $request): void
    {
        // Only for suspicious requests to avoid overhead
        if ($this->isSuspiciousRequest($request)) {
            // Dispatch to queue for async processing
            dispatch(new \App\Jobs\SecurityAnalysisJob($request->all(), $request->ip()));
        }
    }

    /**
     * Check if request is suspicious and needs deep analysis
     */
    private function isSuspiciousRequest(Request $request): bool
    {
        // File uploads
        if ($request->hasFile('*')) {
            return true;
        }

        // Large payloads
        if ($request->getSize() > 1024 * 100) { // 100KB
            return true;
        }

        return false;
    }

    /**
     * Block request with appropriate response
     */
    private function blockRequest(string $reason, Request $request): Response
    {
        $this->logSecurityEvent($reason, $request);

        $statusCodes = [
            'rate_limit_exceeded' => 429,
            'input_validation_failed' => 422,
            'waf_rule_triggered' => 403,
            'fast_check_failed' => 400,
        ];

        return response()->json([
            'error' => 'Request blocked by security policy',
            'code' => $reason
        ], $statusCodes[$reason] ?? 403);
    }

    /**
     * Log security events with sampling
     */
    private function logSecurityEvent(string $reason, Request $request): void
    {
        // Sample logging to avoid log bloat
        if (rand(1, 100) <= ($this->config['logging']['sample_rate'] * 100)) {
            Log::warning('Security event', [
                'reason' => $reason,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $request->route()?->getName(),
                'timestamp' => now()
            ]);
        }

        // Always increment metrics
        $this->incrementSecurityMetric("blocked_requests_total.{$reason}");
    }

    /**
     * Log performance metrics
     */
    private function logSecurityMetrics(Request $request, float $latency): void
    {
        $this->incrementSecurityMetric('handled_requests_total');
        $this->recordLatencyMetric('middleware_latency_ms', $latency * 1000);
    }

    /**
     * Increment security metric (implement with your metrics system)
     */
    private function incrementSecurityMetric(string $metric): void
    {
        // Placeholder - implement with Prometheus/StatsD/etc
        Cache::increment("security_metrics.{$metric}");
    }

    /**
     * Record latency metric
     */
    private function recordLatencyMetric(string $metric, float $value): void
    {
        // Placeholder - implement with your metrics system
        Cache::put("security_metrics.{$metric}.latest", $value, 60);
    }

    /**
     * Get security configuration
     */
    private function getSecurityConfig(): array
    {
        return Cache::remember('security_config', 300, function () {
            return [
                'enabled' => env('SECURITY_MIDDLEWARE_ENABLED', true),
                'max_request_size' => env('SECURITY_MAX_REQUEST_SIZE', 10 * 1024 * 1024), // 10MB
                'rate_limit' => [
                    'per_ip_per_minute' => env('SECURITY_RATE_LIMIT_IP', 60),
                    'per_user_per_minute' => env('SECURITY_RATE_LIMIT_USER', 120),
                    'per_route_per_minute' => env('SECURITY_RATE_LIMIT_ROUTE', 1000),
                ],
                'waf' => [
                    'enabled' => env('SECURITY_WAF_ENABLED', true),
                    'sampling_rate' => env('SECURITY_WAF_SAMPLING_RATE', 0.1),
                    'max_content_scan_size' => env('SECURITY_WAF_MAX_SCAN_SIZE', 64 * 1024), // 64KB
                ],
                'logging' => [
                    'sample_rate' => env('SECURITY_LOGGING_SAMPLE_RATE', 0.1),
                ]
            ];
        });
    }

    /**
     * Compile security patterns for performance
     */
    private function compileSecurityPatterns(): array
    {
        return [
            'sqli' => [
                '/\b(union|select|insert|update|delete|drop|create|alter)\b.*\b(from|where|into)\b/i',
                '/\'\s*(or|and)\s*\'/i',
                '/\-\-|\#|\/\*|\*\//i',
                '/\b(exec|execute|sp_|xp_)\b/i'
            ],
            'xss' => [
                '/<script[^>]*>.*?<\/script>/is',
                '/javascript:/i',
                '/on\w+\s*=/i',
                '/<iframe[^>]*>/i',
                '/<object[^>]*>/i'
            ],
            'waf' => [
                '/\.\.\//i', // Path traversal
                '/\x00/i',   // Null bytes
                '/\${.*?}/i' // Template injection
            ]
        ];
    }
}
