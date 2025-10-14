<?php

namespace App\Services\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Brute Force Protection Service
 *
 * Implements progressive delays, account lockout, and CAPTCHA triggers
 * Uses Redis for distributed state management
 */
class BruteForceProtectionService
{
    /**
     * Redis connection for storing counters
     */
    private $redis;

    /**
     * Configuration cache
     */
    private array $config;

    public function __construct()
    {
        $this->redis = Redis::connection();
        $this->config = config('security.brute_force', []);
    }

    /**
     * Check if request should be blocked due to brute force protection
     */
    public function shouldBlock(Request $request, string $identifier = null): array
    {
        if (!$this->config['enabled']) {
            return ['blocked' => false, 'reason' => 'disabled'];
        }

        $identifier = $identifier ?: $this->getIdentifier($request);
        $attempts = $this->getAttemptCount($identifier);
        $lockoutTime = $this->getLockoutTime($identifier);

        // Check if currently locked out
        if ($lockoutTime && $lockoutTime > time()) {
            return [
                'blocked' => true,
                'reason' => 'locked_out',
                'retry_after' => $lockoutTime - time(),
                'attempts' => $attempts
            ];
        }

        // Check if exceeded max attempts
        if ($attempts >= $this->config['max_attempts']) {
            $this->lockout($identifier);
            return [
                'blocked' => true,
                'reason' => 'max_attempts_exceeded',
                'retry_after' => $this->config['lockout_duration'],
                'attempts' => $attempts
            ];
        }

        // Check if CAPTCHA required
        $captchaRequired = $attempts >= ($this->config['captcha_threshold'] ?? 3);

        return [
            'blocked' => false,
            'captcha_required' => $captchaRequired,
            'attempts' => $attempts,
            'remaining_attempts' => $this->config['max_attempts'] - $attempts
        ];
    }

    /**
     * Record a failed login attempt
     */
    public function recordFailedAttempt(Request $request, string $identifier = null): array
    {
        $identifier = $identifier ?: $this->getIdentifier($request);
        $attempts = $this->incrementAttemptCount($identifier);

        // Apply progressive delay if enabled
        if ($this->config['progressive_delay']) {
            $delay = $this->calculateProgressiveDelay($attempts);
            $this->setProgressiveDelay($identifier, $delay);
        }

        // Log security event
        $this->logBruteForceAttempt($request, $identifier, $attempts);

        // Check if lockout should be triggered
        if ($attempts >= $this->config['max_attempts']) {
            $this->lockout($identifier);
            $this->alertSecurityTeam($request, $identifier, $attempts);
        }

        return [
            'attempts' => $attempts,
            'max_attempts' => $this->config['max_attempts'],
            'lockout_triggered' => $attempts >= $this->config['max_attempts'],
            'progressive_delay' => $this->getProgressiveDelay($identifier)
        ];
    }

    /**
     * Record a successful login (reset counters)
     */
    public function recordSuccessfulAttempt(Request $request, string $identifier = null): void
    {
        $identifier = $identifier ?: $this->getIdentifier($request);
        $this->resetCounters($identifier);

        Log::info('Brute force counters reset after successful login', [
            'identifier' => $this->sanitizeIdentifier($identifier),
            'ip' => $request->ip()
        ]);
    }

    /**
     * Get progressive delay for current request
     */
    public function getProgressiveDelay(string $identifier): int
    {
        $key = "bruteforce:delay:{$identifier}";
        return (int) $this->redis->get($key);
    }

    /**
     * Check if CAPTCHA is required
     */
    public function isCaptchaRequired(Request $request, string $identifier = null): bool
    {
        $identifier = $identifier ?: $this->getIdentifier($request);
        $attempts = $this->getAttemptCount($identifier);

        return $attempts >= ($this->config['captcha_threshold'] ?? 3);
    }

    /**
     * Validate CAPTCHA response
     */
    public function validateCaptcha(Request $request): bool
    {
        // Implement your CAPTCHA validation here
        // This is a placeholder for integration with services like reCAPTCHA
        $captchaResponse = $request->input('captcha_response');

        if (!$captchaResponse) {
            return false;
        }

        // TODO: Integrate with actual CAPTCHA service
        // For now, return true to avoid blocking during development
        return true;
    }

    /**
     * Get unique identifier for brute force tracking
     */
    private function getIdentifier(Request $request): string
    {
        $ip = $request->ip();
        $email = $request->input('email', '');

        // Combine IP and email for more accurate tracking
        return hash('sha256', $ip . '|' . $email);
    }

    /**
     * Get current attempt count
     */
    private function getAttemptCount(string $identifier): int
    {
        $key = "bruteforce:attempts:{$identifier}";
        return (int) $this->redis->get($key);
    }

    /**
     * Increment attempt count
     */
    private function incrementAttemptCount(string $identifier): int
    {
        $key = "bruteforce:attempts:{$identifier}";
        $pipeline = $this->redis->pipeline();

        $pipeline->incr($key);
        $pipeline->expire($key, 3600); // Reset after 1 hour of inactivity

        $results = $pipeline->execute();
        return $results[0];
    }

    /**
     * Get lockout expiry time
     */
    private function getLockoutTime(string $identifier): ?int
    {
        $key = "bruteforce:lockout:{$identifier}";
        $lockoutTime = $this->redis->get($key);

        return $lockoutTime ? (int) $lockoutTime : null;
    }

    /**
     * Set account lockout
     */
    private function lockout(string $identifier): void
    {
        $key = "bruteforce:lockout:{$identifier}";
        $lockoutUntil = time() + $this->config['lockout_duration'];

        $this->redis->setex($key, $this->config['lockout_duration'], $lockoutUntil);
    }

    /**
     * Calculate progressive delay based on attempt count
     */
    private function calculateProgressiveDelay(int $attempts): int
    {
        // Exponential backoff: 2^attempts seconds, max 300 seconds (5 minutes)
        $delay = min(pow(2, $attempts), 300);

        return (int) $delay;
    }

    /**
     * Set progressive delay
     */
    private function setProgressiveDelay(string $identifier, int $delay): void
    {
        if ($delay > 0) {
            $key = "bruteforce:delay:{$identifier}";
            $this->redis->setex($key, $delay, $delay);
        }
    }

    /**
     * Reset all counters for identifier
     */
    private function resetCounters(string $identifier): void
    {
        $keys = [
            "bruteforce:attempts:{$identifier}",
            "bruteforce:lockout:{$identifier}",
            "bruteforce:delay:{$identifier}"
        ];

        foreach ($keys as $key) {
            $this->redis->del($key);
        }
    }

    /**
     * Log brute force attempt
     */
    private function logBruteForceAttempt(Request $request, string $identifier, int $attempts): void
    {
        // Sample logging to avoid log bloat
        $sampleRate = config('security.logging.sample_rate', 0.1);

        if (rand(1, 100) <= ($sampleRate * 100)) {
            Log::warning('Brute force attempt detected', [
                'identifier' => $this->sanitizeIdentifier($identifier),
                'attempts' => $attempts,
                'max_attempts' => $this->config['max_attempts'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $request->route()?->getName(),
                'timestamp' => now()
            ]);
        }
    }

    /**
     * Alert security team of potential attack
     */
    private function alertSecurityTeam(Request $request, string $identifier, int $attempts): void
    {
        // Critical alert for max attempts exceeded
        Log::critical('Brute force attack detected', [
            'identifier' => $this->sanitizeIdentifier($identifier),
            'attempts' => $attempts,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);

        // TODO: Integrate with alerting system (Slack, PagerDuty, etc.)
        // Example: Slack notification, email alert, etc.
    }

    /**
     * Sanitize identifier for logging
     */
    private function sanitizeIdentifier(string $identifier): string
    {
        // Return first 8 characters of hash for logging
        return substr($identifier, 0, 8) . '***';
    }

    /**
     * Get brute force statistics
     */
    public function getStatistics(): array
    {
        $pattern = "bruteforce:*";
        $keys = $this->redis->keys($pattern);

        $stats = [
            'total_tracked_identifiers' => 0,
            'currently_locked_out' => 0,
            'with_progressive_delay' => 0,
            'total_attempts' => 0
        ];

        foreach ($keys as $key) {
            if (str_contains($key, ':attempts:')) {
                $stats['total_tracked_identifiers']++;
                $stats['total_attempts'] += (int) $this->redis->get($key);
            } elseif (str_contains($key, ':lockout:')) {
                $lockoutTime = (int) $this->redis->get($key);
                if ($lockoutTime > time()) {
                    $stats['currently_locked_out']++;
                }
            } elseif (str_contains($key, ':delay:')) {
                $stats['with_progressive_delay']++;
            }
        }

        return $stats;
    }

    /**
     * Manual unlock (for admin use)
     */
    public function manualUnlock(string $identifier): bool
    {
        try {
            $this->resetCounters($identifier);

            Log::info('Manual unlock performed', [
                'identifier' => $this->sanitizeIdentifier($identifier),
                'admin_ip' => request()->ip()
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to perform manual unlock', [
                'identifier' => $this->sanitizeIdentifier($identifier),
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Cleanup expired entries (run periodically)
     */
    public function cleanup(): int
    {
        $pattern = "bruteforce:*";
        $keys = $this->redis->keys($pattern);
        $cleaned = 0;

        foreach ($keys as $key) {
            $ttl = $this->redis->ttl($key);
            if ($ttl < 0) { // Key without expiration or expired
                $this->redis->del($key);
                $cleaned++;
            }
        }

        return $cleaned;
    }
}
