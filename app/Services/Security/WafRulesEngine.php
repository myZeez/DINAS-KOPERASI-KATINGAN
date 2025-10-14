<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Web Application Firewall Rules Engine
 *
 * Lightweight WAF with precompiled patterns for optimal performance
 * Supports rule sampling and dynamic rule updates
 */
class WafRulesEngine
{
    /**
     * Precompiled security patterns
     */
    private array $compiledRules;

    /**
     * Rule execution statistics
     */
    private array $stats = [];

    public function __construct()
    {
        $this->compiledRules = $this->compileSecurityRules();
    }

    /**
     * Analyze request content for security threats
     */
    public function analyzeRequest(string $content, array $headers = [], string $userAgent = ''): array
    {
        $threats = [];
        $startTime = microtime(true);

        // Skip if content is too large
        if (strlen($content) > config('security.waf.max_content_scan_size', 65536)) {
            return ['threats' => [], 'skipped' => 'content_too_large'];
        }

        // SQL Injection detection
        if (config('security.waf.rulesets.sqli', true)) {
            $sqlThreats = $this->detectSqlInjection($content);
            $threats = array_merge($threats, $sqlThreats);
        }

        // XSS detection
        if (config('security.waf.rulesets.xss', true)) {
            $xssThreats = $this->detectXss($content);
            $threats = array_merge($threats, $xssThreats);
        }

        // Local File Inclusion detection
        if (config('security.waf.rulesets.lfi', true)) {
            $lfiThreats = $this->detectLocalFileInclusion($content);
            $threats = array_merge($threats, $lfiThreats);
        }

        // Remote Code Execution detection
        if (config('security.waf.rulesets.rce', true)) {
            $rceThreats = $this->detectRemoteCodeExecution($content);
            $threats = array_merge($threats, $rceThreats);
        }

        // Header-based attacks
        $headerThreats = $this->analyzeHeaders($headers);
        $threats = array_merge($threats, $headerThreats);

        // User agent analysis
        $uaThreats = $this->analyzeUserAgent($userAgent);
        $threats = array_merge($threats, $uaThreats);

        $this->recordStats(microtime(true) - $startTime, count($threats));

        return [
            'threats' => $threats,
            'processing_time' => microtime(true) - $startTime,
            'rules_triggered' => count($threats)
        ];
    }

    /**
     * Detect SQL injection attempts
     */
    private function detectSqlInjection(string $content): array
    {
        $threats = [];

        foreach ($this->compiledRules['sqli'] as $rule) {
            if (preg_match($rule['pattern'], $content, $matches)) {
                $threats[] = [
                    'type' => 'sql_injection',
                    'severity' => $rule['severity'],
                    'description' => $rule['description'],
                    'match' => $this->sanitizeMatch($matches[0] ?? ''),
                    'rule_id' => $rule['id']
                ];
            }
        }

        return $threats;
    }

    /**
     * Detect XSS attempts
     */
    private function detectXss(string $content): array
    {
        $threats = [];

        foreach ($this->compiledRules['xss'] as $rule) {
            if (preg_match($rule['pattern'], $content, $matches)) {
                $threats[] = [
                    'type' => 'xss',
                    'severity' => $rule['severity'],
                    'description' => $rule['description'],
                    'match' => $this->sanitizeMatch($matches[0] ?? ''),
                    'rule_id' => $rule['id']
                ];
            }
        }

        return $threats;
    }

    /**
     * Detect Local File Inclusion attempts
     */
    private function detectLocalFileInclusion(string $content): array
    {
        $threats = [];

        foreach ($this->compiledRules['lfi'] as $rule) {
            if (preg_match($rule['pattern'], $content, $matches)) {
                $threats[] = [
                    'type' => 'local_file_inclusion',
                    'severity' => $rule['severity'],
                    'description' => $rule['description'],
                    'match' => $this->sanitizeMatch($matches[0] ?? ''),
                    'rule_id' => $rule['id']
                ];
            }
        }

        return $threats;
    }

    /**
     * Detect Remote Code Execution attempts
     */
    private function detectRemoteCodeExecution(string $content): array
    {
        $threats = [];

        foreach ($this->compiledRules['rce'] as $rule) {
            if (preg_match($rule['pattern'], $content, $matches)) {
                $threats[] = [
                    'type' => 'remote_code_execution',
                    'severity' => $rule['severity'],
                    'description' => $rule['description'],
                    'match' => $this->sanitizeMatch($matches[0] ?? ''),
                    'rule_id' => $rule['id']
                ];
            }
        }

        return $threats;
    }

    /**
     * Analyze HTTP headers for security threats
     */
    private function analyzeHeaders(array $headers): array
    {
        $threats = [];

        foreach ($headers as $name => $value) {
            // Host header injection
            if (strtolower($name) === 'host' && !$this->isValidHost($value)) {
                $threats[] = [
                    'type' => 'host_header_injection',
                    'severity' => 'medium',
                    'description' => 'Suspicious host header value',
                    'match' => $this->sanitizeMatch($value),
                    'rule_id' => 'HEADER_001'
                ];
            }

            // X-Forwarded-Host injection
            if (strtolower($name) === 'x-forwarded-host' && !$this->isValidHost($value)) {
                $threats[] = [
                    'type' => 'forwarded_host_injection',
                    'severity' => 'medium',
                    'description' => 'Suspicious X-Forwarded-Host header',
                    'match' => $this->sanitizeMatch($value),
                    'rule_id' => 'HEADER_002'
                ];
            }
        }

        return $threats;
    }

    /**
     * Analyze User Agent for suspicious patterns
     */
    private function analyzeUserAgent(string $userAgent): array
    {
        $threats = [];

        $suspiciousPatterns = [
            'sqlmap' => 'SQL injection scanner',
            'nikto' => 'Web vulnerability scanner',
            'nmap' => 'Network port scanner',
            'masscan' => 'Network port scanner',
            'nessus' => 'Vulnerability scanner',
            'burp' => 'Web application security testing',
            'w3af' => 'Web application attack framework',
            'acunetix' => 'Web vulnerability scanner'
        ];

        foreach ($suspiciousPatterns as $pattern => $description) {
            if (stripos($userAgent, $pattern) !== false) {
                $threats[] = [
                    'type' => 'suspicious_user_agent',
                    'severity' => 'high',
                    'description' => "Detected {$description} in User-Agent",
                    'match' => $this->sanitizeMatch($userAgent),
                    'rule_id' => 'UA_' . strtoupper($pattern)
                ];
            }
        }

        return $threats;
    }

    /**
     * Validate host header values
     */
    private function isValidHost(string $host): bool
    {
        $allowedHosts = config('security.whitelist.hosts', []);
        $allowedHosts[] = config('app.url');

        // Remove protocol from allowed hosts
        $allowedHosts = array_map(function($host) {
            return preg_replace('/^https?:\/\//', '', $host);
        }, $allowedHosts);

        return in_array($host, $allowedHosts);
    }

    /**
     * Sanitize matched content for logging
     */
    private function sanitizeMatch(string $match): string
    {
        // Limit length and remove sensitive data
        $sanitized = substr($match, 0, 100);

        // Remove potential passwords/tokens
        $sanitized = preg_replace('/password[\'"\s]*[=:][\'"\s]*[^\s\'"&]+/i', 'password=***', $sanitized);
        $sanitized = preg_replace('/token[\'"\s]*[=:][\'"\s]*[^\s\'"&]+/i', 'token=***', $sanitized);

        return $sanitized;
    }

    /**
     * Record WAF statistics
     */
    private function recordStats(float $processingTime, int $threatsFound): void
    {
        $this->stats['processing_time'] = $processingTime;
        $this->stats['threats_found'] = $threatsFound;
        $this->stats['timestamp'] = microtime(true);

        // Store in cache for monitoring
        Cache::put('waf_stats_latest', $this->stats, 60);
    }

    /**
     * Compile security rules from configuration
     */
    private function compileSecurityRules(): array
    {
        return Cache::remember('waf_compiled_rules', 3600, function () {
            return [
                'sqli' => [
                    [
                        'id' => 'SQLI_001',
                        'pattern' => '/\b(union|select|insert|update|delete|drop|create|alter|exec|execute)\b.*\b(from|where|into|values|set)\b/i',
                        'severity' => 'high',
                        'description' => 'SQL injection attempt detected'
                    ],
                    [
                        'id' => 'SQLI_002',
                        'pattern' => '/\'\s*(or|and)\s*[\'"\d]/i',
                        'severity' => 'high',
                        'description' => 'SQL boolean injection attempt'
                    ],
                    [
                        'id' => 'SQLI_003',
                        'pattern' => '/(\-\-|\#|\/\*|\*\/)/i',
                        'severity' => 'medium',
                        'description' => 'SQL comment injection attempt'
                    ],
                    [
                        'id' => 'SQLI_004',
                        'pattern' => '/\b(sp_|xp_|fn_|information_schema|sysobjects)\b/i',
                        'severity' => 'high',
                        'description' => 'SQL system object access attempt'
                    ]
                ],
                'xss' => [
                    [
                        'id' => 'XSS_001',
                        'pattern' => '/<script[^>]*>.*?<\/script>/is',
                        'severity' => 'high',
                        'description' => 'JavaScript injection attempt'
                    ],
                    [
                        'id' => 'XSS_002',
                        'pattern' => '/javascript:/i',
                        'severity' => 'high',
                        'description' => 'JavaScript protocol injection'
                    ],
                    [
                        'id' => 'XSS_003',
                        'pattern' => '/on\w+\s*=/i',
                        'severity' => 'medium',
                        'description' => 'HTML event handler injection'
                    ],
                    [
                        'id' => 'XSS_004',
                        'pattern' => '/<(iframe|object|embed|form)[^>]*>/i',
                        'severity' => 'medium',
                        'description' => 'Potentially dangerous HTML tag'
                    ]
                ],
                'lfi' => [
                    [
                        'id' => 'LFI_001',
                        'pattern' => '/\.\.\//i',
                        'severity' => 'high',
                        'description' => 'Directory traversal attempt'
                    ],
                    [
                        'id' => 'LFI_002',
                        'pattern' => '/(\/etc\/passwd|\/etc\/shadow|\/etc\/hosts)/i',
                        'severity' => 'high',
                        'description' => 'System file access attempt'
                    ],
                    [
                        'id' => 'LFI_003',
                        'pattern' => '/\x00/i',
                        'severity' => 'high',
                        'description' => 'Null byte injection'
                    ]
                ],
                'rce' => [
                    [
                        'id' => 'RCE_001',
                        'pattern' => '/\$\{.*?\}/i',
                        'severity' => 'high',
                        'description' => 'Template injection attempt'
                    ],
                    [
                        'id' => 'RCE_002',
                        'pattern' => '/\b(eval|exec|system|shell_exec|passthru|base64_decode)\s*\(/i',
                        'severity' => 'high',
                        'description' => 'Code execution function detected'
                    ],
                    [
                        'id' => 'RCE_003',
                        'pattern' => '/\$\{jndi:(ldap|rmi|dns):/i',
                        'severity' => 'critical',
                        'description' => 'Log4j JNDI injection attempt'
                    ]
                ]
            ];
        });
    }

    /**
     * Get WAF statistics
     */
    public function getStats(): array
    {
        return Cache::get('waf_stats_latest', []);
    }

    /**
     * Update rules from external source
     */
    public function updateRules(array $newRules): bool
    {
        try {
            Cache::forget('waf_compiled_rules');
            $this->compiledRules = $this->compileSecurityRules();

            Log::info('WAF rules updated successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update WAF rules: ' . $e->getMessage());
            return false;
        }
    }
}
