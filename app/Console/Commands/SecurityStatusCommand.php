<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;

class SecurityStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:status
                           {--detailed : Show detailed statistics}
                           {--json : Output in JSON format}
                           {--reset : Reset all counters}';

    /**
     * The console command description.
     */
    protected $description = 'Display security middleware status and statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('reset')) {
            return $this->resetCounters();
        }

        $status = $this->getSecurityStatus();

        if ($this->option('json')) {
            $this->line(json_encode($status, JSON_PRETTY_PRINT));
            return 0;
        }

        $this->displayStatus($status);

        if ($this->option('detailed')) {
            $this->displayDetailedStats();
        }

        return 0;
    }

    /**
     * Get overall security status
     */
    private function getSecurityStatus(): array
    {
        return [
            'middleware_enabled' => Config::get('security.enabled', false),
            'kill_switch' => Config::get('security.kill_switch', false),
            'rate_limiting' => [
                'enabled' => Config::get('security.rate_limiting.enabled', false),
                'requests_per_minute' => Config::get('security.rate_limiting.requests_per_minute', 60),
                'current_blocked_ips' => $this->getBlockedIpsCount(),
            ],
            'waf' => [
                'enabled' => Config::get('security.waf.enabled', false),
                'total_threats_detected' => $this->getTotalThreatsDetected(),
                'threats_last_24h' => $this->getThreatsLast24h(),
            ],
            'brute_force_protection' => [
                'enabled' => Config::get('security.brute_force.enabled', false),
                'blocked_attempts' => $this->getBruteForceBlocked(),
            ],
            'file_upload_security' => [
                'enabled' => Config::get('security.file_upload.enabled', false),
                'files_scanned' => $this->getFilesScanned(),
                'threats_found' => $this->getFileThreats(),
            ],
            'performance' => [
                'avg_latency_ms' => $this->getAverageLatency(),
                'requests_processed' => $this->getTotalRequests(),
                'uptime' => $this->getUptime(),
            ]
        ];
    }

    /**
     * Display status in formatted output
     */
    private function displayStatus(array $status): void
    {
        $this->info('🛡️  Security Middleware Status');
        $this->line('=====================================');

        // Overall Status
        $this->line('');
        $this->info('📊 Overall Status:');
        $this->line('  Middleware: ' . ($status['middleware_enabled'] ? '✅ Enabled' : '❌ Disabled'));
        $this->line('  Kill Switch: ' . ($status['kill_switch'] ? '🛑 ACTIVE' : '✅ Normal'));

        // Rate Limiting
        $this->line('');
        $this->info('🚦 Rate Limiting:');
        $this->line('  Status: ' . ($status['rate_limiting']['enabled'] ? '✅ Enabled' : '❌ Disabled'));
        $this->line('  Limit: ' . $status['rate_limiting']['requests_per_minute'] . ' requests/minute');
        $this->line('  Blocked IPs: ' . $status['rate_limiting']['current_blocked_ips']);

        // WAF
        $this->line('');
        $this->info('🔍 Web Application Firewall:');
        $this->line('  Status: ' . ($status['waf']['enabled'] ? '✅ Enabled' : '❌ Disabled'));
        $this->line('  Total Threats: ' . number_format($status['waf']['total_threats_detected']));
        $this->line('  Last 24h: ' . number_format($status['waf']['threats_last_24h']));

        // Brute Force Protection
        $this->line('');
        $this->info('🔒 Brute Force Protection:');
        $this->line('  Status: ' . ($status['brute_force_protection']['enabled'] ? '✅ Enabled' : '❌ Disabled'));
        $this->line('  Blocked Attempts: ' . number_format($status['brute_force_protection']['blocked_attempts']));

        // File Upload Security
        $this->line('');
        $this->info('📁 File Upload Security:');
        $this->line('  Status: ' . ($status['file_upload_security']['enabled'] ? '✅ Enabled' : '❌ Disabled'));
        $this->line('  Files Scanned: ' . number_format($status['file_upload_security']['files_scanned']));
        $this->line('  Threats Found: ' . number_format($status['file_upload_security']['threats_found']));

        // Performance
        $this->line('');
        $this->info('⚡ Performance:');
        $this->line('  Avg Latency: ' . round($status['performance']['avg_latency_ms'], 2) . 'ms');
        $this->line('  Requests Processed: ' . number_format($status['performance']['requests_processed']));
        $this->line('  Uptime: ' . $status['performance']['uptime']);

        $this->line('');
    }

    /**
     * Display detailed statistics
     */
    private function displayDetailedStats(): void
    {
        $this->info('📈 Detailed Statistics');
        $this->line('======================');

        // Rate Limiting Details
        $this->line('');
        $this->info('Rate Limiting Details:');
        $rateLimitStats = $this->getRateLimitStats();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Rate Limited', number_format($rateLimitStats['total_limited'])],
                ['Current Hour', number_format($rateLimitStats['current_hour'])],
                ['Peak Hour', number_format($rateLimitStats['peak_hour'])],
                ['Top IP (requests)', $rateLimitStats['top_ip']],
            ]
        );

        // WAF Threat Breakdown
        $this->line('');
        $this->info('WAF Threat Breakdown:');
        $threatStats = $this->getWafThreatStats();
        $this->table(
            ['Threat Type', 'Count', 'Last Detected'],
            $threatStats
        );

        // Performance Metrics
        $this->line('');
        $this->info('Performance Metrics:');
        $perfStats = $this->getPerformanceStats();
        $this->table(
            ['Metric', 'Value'],
            [
                ['P50 Latency', round($perfStats['p50'], 2) . 'ms'],
                ['P95 Latency', round($perfStats['p95'], 2) . 'ms'],
                ['P99 Latency', round($perfStats['p99'], 2) . 'ms'],
                ['Requests/sec (avg)', round($perfStats['rps'], 2)],
                ['Memory Usage', $perfStats['memory']],
            ]
        );

        // System Health
        $this->line('');
        $this->info('System Health:');
        $healthStats = $this->getSystemHealth();
        $this->table(
            ['Component', 'Status', 'Details'],
            $healthStats
        );
    }

    /**
     * Reset all security counters
     */
    private function resetCounters(): int
    {
        if (!$this->confirm('Are you sure you want to reset all security counters?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $patterns = [
            'security:rate_limit:*',
            'security:waf:*',
            'security:brute_force:*',
            'security:performance:*',
            'security:blocked:*'
        ];

        $deletedKeys = 0;
        foreach ($patterns as $pattern) {
            $keys = Redis::keys($pattern);
            if (!empty($keys)) {
                Redis::del($keys);
                $deletedKeys += count($keys);
            }
        }

        $this->info("✅ Reset complete. Deleted $deletedKeys keys.");
        return 0;
    }

    /**
     * Get count of currently blocked IPs
     */
    private function getBlockedIpsCount(): int
    {
        $keys = Redis::keys('security:rate_limit:*');
        $blockedCount = 0;

        foreach ($keys as $key) {
            $count = Redis::get($key);
            if ($count && $count > Config::get('security.rate_limiting.requests_per_minute', 60)) {
                $blockedCount++;
            }
        }

        return $blockedCount;
    }

    /**
     * Get total threats detected
     */
    private function getTotalThreatsDetected(): int
    {
        return (int) Redis::get('security:waf:total_threats') ?: 0;
    }

    /**
     * Get threats detected in last 24 hours
     */
    private function getThreatsLast24h(): int
    {
        $today = date('Y-m-d');
        return (int) Redis::get("security:waf:threats:$today") ?: 0;
    }

    /**
     * Get brute force blocked attempts
     */
    private function getBruteForceBlocked(): int
    {
        return (int) Redis::get('security:brute_force:total_blocked') ?: 0;
    }

    /**
     * Get files scanned count
     */
    private function getFilesScanned(): int
    {
        return (int) Redis::get('security:file_upload:scanned') ?: 0;
    }

    /**
     * Get file threats found
     */
    private function getFileThreats(): int
    {
        return (int) Redis::get('security:file_upload:threats') ?: 0;
    }

    /**
     * Get average latency
     */
    private function getAverageLatency(): float
    {
        $totalLatency = (float) Redis::get('security:performance:total_latency') ?: 0;
        $totalRequests = (int) Redis::get('security:performance:total_requests') ?: 1;

        return $totalLatency / $totalRequests;
    }

    /**
     * Get total requests processed
     */
    private function getTotalRequests(): int
    {
        return (int) Redis::get('security:performance:total_requests') ?: 0;
    }

    /**
     * Get system uptime
     */
    private function getUptime(): string
    {
        $startTime = Redis::get('security:performance:start_time');
        if (!$startTime) {
            return 'Unknown';
        }

        $uptime = time() - $startTime;
        $days = floor($uptime / 86400);
        $hours = floor(($uptime % 86400) / 3600);
        $minutes = floor(($uptime % 3600) / 60);

        return "{$days}d {$hours}h {$minutes}m";
    }

    /**
     * Get detailed rate limiting statistics
     */
    private function getRateLimitStats(): array
    {
        $currentHour = date('Y-m-d-H');
        $keys = Redis::keys('security:rate_limit:*');

        $totalLimited = 0;
        $topIp = 'None';
        $maxRequests = 0;

        foreach ($keys as $key) {
            $count = Redis::get($key);
            if ($count > Config::get('security.rate_limiting.requests_per_minute', 60)) {
                $totalLimited++;
            }

            if ($count > $maxRequests) {
                $maxRequests = $count;
                $topIp = str_replace('security:rate_limit:', '', $key) . " ($count)";
            }
        }

        return [
            'total_limited' => $totalLimited,
            'current_hour' => Redis::get("security:rate_limit:hour:$currentHour") ?: 0,
            'peak_hour' => Redis::get('security:rate_limit:peak_hour') ?: 0,
            'top_ip' => $topIp,
        ];
    }

    /**
     * Get WAF threat statistics
     */
    private function getWafThreatStats(): array
    {
        $threatTypes = ['sql_injection', 'xss', 'path_traversal', 'command_injection', 'php_injection'];
        $stats = [];

        foreach ($threatTypes as $type) {
            $count = Redis::get("security:waf:threats:$type") ?: 0;
            $lastDetected = Redis::get("security:waf:last:$type") ?: 'Never';

            if ($lastDetected !== 'Never') {
                $lastDetected = date('Y-m-d H:i:s', $lastDetected);
            }

            $stats[] = [
                ucfirst(str_replace('_', ' ', $type)),
                number_format($count),
                $lastDetected
            ];
        }

        return $stats;
    }

    /**
     * Get performance statistics
     */
    private function getPerformanceStats(): array
    {
        // Get latency percentiles from Redis sorted set
        $latencies = Redis::zrange('security:performance:latencies', 0, -1);
        sort($latencies);

        $count = count($latencies);
        $p50 = $count > 0 ? $latencies[floor($count * 0.5)] : 0;
        $p95 = $count > 0 ? $latencies[floor($count * 0.95)] : 0;
        $p99 = $count > 0 ? $latencies[floor($count * 0.99)] : 0;

        $totalRequests = $this->getTotalRequests();
        $uptime = time() - (Redis::get('security:performance:start_time') ?: time());
        $rps = $uptime > 0 ? $totalRequests / $uptime : 0;

        return [
            'p50' => $p50,
            'p95' => $p95,
            'p99' => $p99,
            'rps' => $rps,
            'memory' => $this->formatBytes(memory_get_usage(true)),
        ];
    }

    /**
     * Get system health status
     */
    private function getSystemHealth(): array
    {
        $health = [];

        // Redis health
        try {
            Redis::ping();
            $health[] = ['Redis', '✅ Healthy', 'Connected'];
        } catch (\Exception $e) {
            $health[] = ['Redis', '❌ Error', $e->getMessage()];
        }

        // Queue health
        $queueSize = Redis::llen('queues:security');
        $queueStatus = $queueSize < 100 ? '✅ Healthy' : '⚠️ Warning';
        $health[] = ['Security Queue', $queueStatus, "$queueSize jobs"];

        // Log file health
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logSize = filesize($logFile);
            $logStatus = $logSize < 100 * 1024 * 1024 ? '✅ Healthy' : '⚠️ Large';
            $health[] = ['Log File', $logStatus, $this->formatBytes($logSize)];
        } else {
            $health[] = ['Log File', '❌ Missing', 'Not found'];
        }

        // Disk space
        $diskFree = disk_free_space(storage_path());
        $diskTotal = disk_total_space(storage_path());
        $diskUsage = (($diskTotal - $diskFree) / $diskTotal) * 100;
        $diskStatus = $diskUsage < 80 ? '✅ Healthy' : '⚠️ Low Space';
        $health[] = ['Disk Space', $diskStatus, round($diskUsage, 1) . '% used'];

        return $health;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
