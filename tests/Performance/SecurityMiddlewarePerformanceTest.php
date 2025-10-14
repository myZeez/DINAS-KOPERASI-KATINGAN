<?php

namespace Tests\Performance;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use App\Http\Middleware\SecurityMiddleware;

class SecurityMiddlewarePerformanceTest extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure for performance testing
        Config::set('security.enabled', true);
        Config::set('security.kill_switch', false);
        Config::set('security.rate_limiting.enabled', true);
        Config::set('security.waf.enabled', true);
        Config::set('security.logging.enabled', false); // Disable logging for performance

        Redis::flushall();
    }

    /** @test */
    public function it_meets_latency_requirements_for_normal_requests()
    {
        $iterations = 100;
        $latencies = [];

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);

            $response = $this->get('/api/test');

            $latency = (microtime(true) - $start) * 1000;
            $latencies[] = $latency;
        }

        $avgLatency = array_sum($latencies) / count($latencies);
        $p99Latency = $this->calculatePercentile($latencies, 99);
        $p95Latency = $this->calculatePercentile($latencies, 95);

        echo "\nPerformance Metrics:\n";
        echo "Average Latency: " . round($avgLatency, 2) . "ms\n";
        echo "P95 Latency: " . round($p95Latency, 2) . "ms\n";
        echo "P99 Latency: " . round($p99Latency, 2) . "ms\n";

        $this->assertLessThan(10, $p99Latency, 'P99 latency should be under 10ms');
        $this->assertLessThan(5, $avgLatency, 'Average latency should be under 5ms');
    }

    /** @test */
    public function it_handles_high_throughput_requests()
    {
        $requestCount = 1000;
        $concurrency = 10;
        $batchSize = $requestCount / $concurrency;

        $start = microtime(true);

        // Simulate concurrent requests
        $promises = [];
        for ($i = 0; $i < $concurrency; $i++) {
            $promises[] = $this->simulateBatchRequests($batchSize, $i);
        }

        // Wait for all batches to complete
        $results = array_merge(...$promises);

        $totalTime = microtime(true) - $start;
        $throughput = $requestCount / $totalTime;

        echo "\nThroughput Test Results:\n";
        echo "Total Requests: $requestCount\n";
        echo "Total Time: " . round($totalTime, 2) . "s\n";
        echo "Throughput: " . round($throughput, 2) . " RPS\n";

        $this->assertGreaterThan(100, $throughput, 'Should handle at least 100 RPS');

        $successfulRequests = array_filter($results, function($result) {
            return $result['status'] < 400;
        });

        $successRate = count($successfulRequests) / count($results) * 100;
        echo "Success Rate: " . round($successRate, 2) . "%\n";

        $this->assertGreaterThan(95, $successRate, 'Success rate should be above 95%');
    }

    /** @test */
    public function it_efficiently_detects_security_threats()
    {
        $maliciousPayloads = [
            "1' OR '1'='1",
            "<script>alert('xss')</script>",
            "../../../etc/passwd",
            "; cat /etc/passwd",
            "<?php system(\$_GET['cmd']); ?>",
            "')(|(objectclass=*))",
            "eval($_POST['code'])",
            "%2e%2e%2f%2e%2e%2f%2e%2e%2fetc%2fpasswd"
        ];

        $threatDetectionTimes = [];

        foreach ($maliciousPayloads as $payload) {
            $start = microtime(true);

            $response = $this->post('/api/test', ['input' => $payload]);

            $detectionTime = (microtime(true) - $start) * 1000;
            $threatDetectionTimes[] = $detectionTime;

            $this->assertEquals(403, $response->getStatusCode(),
                "Should detect threat in payload: $payload");
        }

        $avgDetectionTime = array_sum($threatDetectionTimes) / count($threatDetectionTimes);
        $maxDetectionTime = max($threatDetectionTimes);

        echo "\nThreat Detection Performance:\n";
        echo "Average Detection Time: " . round($avgDetectionTime, 2) . "ms\n";
        echo "Max Detection Time: " . round($maxDetectionTime, 2) . "ms\n";

        $this->assertLessThan(8, $avgDetectionTime, 'Average threat detection should be under 8ms');
        $this->assertLessThan(15, $maxDetectionTime, 'Max threat detection should be under 15ms');
    }

    /** @test */
    public function it_maintains_performance_under_rate_limiting()
    {
        $ip = '192.168.1.100';
        $requestsUnderLimit = 8; // Under the limit of 10
        $latencies = [];

        for ($i = 0; $i < $requestsUnderLimit; $i++) {
            $start = microtime(true);

            $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                            ->get('/api/test');

            $latency = (microtime(true) - $start) * 1000;
            $latencies[] = $latency;

            $this->assertNotEquals(429, $response->getStatusCode());
        }

        $avgLatency = array_sum($latencies) / count($latencies);

        echo "\nRate Limiting Performance:\n";
        echo "Average Latency (under limit): " . round($avgLatency, 2) . "ms\n";

        $this->assertLessThan(6, $avgLatency, 'Rate limiting should not significantly impact latency');

        // Test rate limiting response time
        $start = microtime(true);
        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                        ->get('/api/test');
        $rateLimitLatency = (microtime(true) - $start) * 1000;

        echo "Rate Limit Response Time: " . round($rateLimitLatency, 2) . "ms\n";

        // Rate limit response should still be fast
        $this->assertLessThan(5, $rateLimitLatency, 'Rate limit response should be fast');
    }

    /** @test */
    public function it_handles_large_payloads_efficiently()
    {
        $payloadSizes = [1024, 5120, 10240]; // 1KB, 5KB, 10KB

        foreach ($payloadSizes as $size) {
            $payload = str_repeat('A', $size);

            $start = microtime(true);

            $response = $this->post('/api/test', ['data' => $payload]);

            $processingTime = (microtime(true) - $start) * 1000;

            echo "\nPayload Size: " . ($size/1024) . "KB - Processing Time: " . round($processingTime, 2) . "ms\n";

            // Large payloads should be rejected quickly
            $this->assertEquals(400, $response->getStatusCode());
            $this->assertLessThan(20, $processingTime, "Large payload processing should be under 20ms for {$size} bytes");
        }
    }

    /** @test */
    public function it_efficiently_manages_memory_usage()
    {
        $initialMemory = memory_get_usage(true);

        // Perform 100 requests
        for ($i = 0; $i < 100; $i++) {
            $this->get('/api/test');

            if ($i % 10 == 0) {
                $currentMemory = memory_get_usage(true);
                $memoryIncrease = $currentMemory - $initialMemory;

                echo "\nRequest $i - Memory Usage: " . round($memoryIncrease / 1024 / 1024, 2) . "MB\n";

                // Memory increase should be minimal
                $this->assertLessThan(10 * 1024 * 1024, $memoryIncrease, 'Memory usage should not exceed 10MB');
            }
        }

        $finalMemory = memory_get_usage(true);
        $totalIncrease = $finalMemory - $initialMemory;

        echo "Total Memory Increase: " . round($totalIncrease / 1024 / 1024, 2) . "MB\n";

        $this->assertLessThan(15 * 1024 * 1024, $totalIncrease, 'Total memory increase should be under 15MB');
    }

    /** @test */
    public function it_maintains_redis_performance()
    {
        $iterations = 100;
        $redisTimes = [];

        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);

            // Simulate middleware Redis operations
            Redis::pipeline(function ($pipe) use ($i) {
                $pipe->incr("test:counter:$i");
                $pipe->expire("test:counter:$i", 60);
            });

            $redisTime = (microtime(true) - $start) * 1000;
            $redisTimes[] = $redisTime;
        }

        $avgRedisTime = array_sum($redisTimes) / count($redisTimes);
        $maxRedisTime = max($redisTimes);

        echo "\nRedis Performance:\n";
        echo "Average Redis Operation Time: " . round($avgRedisTime, 2) . "ms\n";
        echo "Max Redis Operation Time: " . round($maxRedisTime, 2) . "ms\n";

        $this->assertLessThan(3, $avgRedisTime, 'Average Redis operations should be under 3ms');
        $this->assertLessThan(10, $maxRedisTime, 'Max Redis operation should be under 10ms');
    }

    /** @test */
    public function it_scales_with_concurrent_users()
    {
        $userCounts = [1, 5, 10, 20];

        foreach ($userCounts as $userCount) {
            $latencies = [];

            // Simulate concurrent users
            for ($user = 0; $user < $userCount; $user++) {
                $start = microtime(true);

                $response = $this->withServerVariables(['REMOTE_ADDR' => "192.168.1.$user"])
                                ->get('/api/test');

                $latency = (microtime(true) - $start) * 1000;
                $latencies[] = $latency;
            }

            $avgLatency = array_sum($latencies) / count($latencies);

            echo "\nConcurrent Users: $userCount - Avg Latency: " . round($avgLatency, 2) . "ms\n";

            // Latency should scale reasonably with user count
            $maxAcceptableLatency = 5 + ($userCount * 0.5); // Base 5ms + 0.5ms per user
            $this->assertLessThan($maxAcceptableLatency, $avgLatency,
                "Latency should scale reasonably with $userCount users");
        }
    }

    /**
     * Calculate percentile value from array of numbers
     */
    private function calculatePercentile(array $values, int $percentile): float
    {
        sort($values);
        $index = ceil(($percentile / 100) * count($values)) - 1;
        return $values[$index] ?? 0;
    }

    /**
     * Simulate batch requests for throughput testing
     */
    private function simulateBatchRequests(int $count, int $batchId): array
    {
        $results = [];

        for ($i = 0; $i < $count; $i++) {
            $start = microtime(true);

            $response = $this->withServerVariables(['REMOTE_ADDR' => "192.168.$batchId.$i"])
                            ->get('/api/test');

            $duration = (microtime(true) - $start) * 1000;

            $results[] = [
                'status' => $response->getStatusCode(),
                'duration' => $duration,
                'batch' => $batchId,
                'request' => $i
            ];
        }

        return $results;
    }
}
