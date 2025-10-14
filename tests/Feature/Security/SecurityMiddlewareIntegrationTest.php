<?php

namespace Tests\Feature\Security;

use App\Http\Middleware\SecurityMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SecurityMiddlewareIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Enable security middleware for testing
        Config::set('security.enabled', true);
        Config::set('security.kill_switch', false);
        Config::set('security.rate_limiting.enabled', true);
        Config::set('security.rate_limiting.requests_per_minute', 10);
        Config::set('security.waf.enabled', true);

        // Clear Redis for clean tests
        Redis::flushall();
    }

    /** @test */
    public function it_successfully_processes_normal_requests()
    {
        $response = $this->get('/');

        $this->assertNotEquals(429, $response->getStatusCode());
        $this->assertNotEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        $ip = '192.168.1.100';

        // Make requests up to the limit
        for ($i = 0; $i < 10; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                            ->get('/');
            $this->assertNotEquals(429, $response->getStatusCode());
        }

        // The next request should be rate limited
        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                        ->get('/');
        $this->assertEquals(429, $response->getStatusCode());
    }

    /** @test */
    public function it_blocks_sql_injection_attempts()
    {
        $response = $this->post('/test', [
            'search' => "1' OR '1'='1"
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Security threat detected', $response->getContent());
    }

    /** @test */
    public function it_blocks_xss_attempts()
    {
        $response = $this->post('/test', [
            'comment' => "<script>alert('xss')</script>"
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Security threat detected', $response->getContent());
    }

    /** @test */
    public function it_blocks_path_traversal_attempts()
    {
        $response = $this->post('/test', [
            'file' => "../../../etc/passwd"
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Security threat detected', $response->getContent());
    }

    /** @test */
    public function it_blocks_command_injection_attempts()
    {
        $response = $this->post('/test', [
            'input' => "; cat /etc/passwd"
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Security threat detected', $response->getContent());
    }

    /** @test */
    public function it_adds_security_headers_to_responses()
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options');
        $response->assertHeader('X-Content-Type-Options');
        $response->assertHeader('X-XSS-Protection');
        $response->assertHeader('Referrer-Policy');
        $response->assertHeader('Content-Security-Policy');
    }

    /** @test */
    public function it_handles_malicious_headers()
    {
        $response = $this->withHeaders([
            'User-Agent' => '<script>alert("xss")</script>',
            'Referer' => 'javascript:alert("xss")'
        ])->get('/');

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_handles_malicious_query_parameters()
    {
        $response = $this->get('/test?search=<script>alert("xss")</script>');

        $this->assertEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_respects_whitelisted_ips()
    {
        Config::set('security.whitelist.ips', ['192.168.1.100']);

        // Make many requests from whitelisted IP
        for ($i = 0; $i < 20; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => '192.168.1.100'])
                            ->post('/test', ['input' => "1' OR '1'='1"]);

            // Should not be blocked despite malicious input and rate limiting
            $this->assertNotEquals(429, $response->getStatusCode());
            $this->assertNotEquals(403, $response->getStatusCode());
        }
    }

    /** @test */
    public function it_handles_kill_switch()
    {
        Config::set('security.kill_switch', true);

        // Should allow malicious requests when kill switch is enabled
        $response = $this->post('/test', [
            'input' => "1' OR '1'='1"
        ]);

        $this->assertNotEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_measures_performance()
    {
        $start = microtime(true);

        $response = $this->get('/');

        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(50, $duration, 'Request should complete within 50ms including middleware');
        $this->assertNotNull($response->headers->get('X-Security-Latency'));
    }

    /** @test */
    public function it_handles_file_upload_security()
    {
        // Create a malicious PHP file
        $maliciousContent = '<?php system($_GET["cmd"]); ?>';
        $maliciousFile = tmpfile();
        fwrite($maliciousFile, $maliciousContent);
        $maliciousPath = stream_get_meta_data($maliciousFile)['uri'];

        $response = $this->post('/upload', [], [
            'file' => new \Illuminate\Http\UploadedFile(
                $maliciousPath,
                'malicious.php',
                'application/x-php',
                strlen($maliciousContent),
                UPLOAD_ERR_OK,
                true
            )
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('File upload rejected', $response->getContent());

        fclose($maliciousFile);
    }

    /** @test */
    public function it_handles_brute_force_protection()
    {
        $ip = '192.168.1.200';

        // Simulate multiple failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                            ->post('/login', [
                                'email' => 'admin@test.com',
                                'password' => 'wrong_password'
                            ]);
        }

        // Next login attempt should be blocked
        $response = $this->withServerVariables(['REMOTE_ADDR' => $ip])
                        ->post('/login', [
                            'email' => 'admin@test.com',
                            'password' => 'correct_password'
                        ]);

        $this->assertEquals(429, $response->getStatusCode());
        $this->assertStringContainsString('Too many failed attempts', $response->getContent());
    }

    /** @test */
    public function it_handles_concurrent_requests()
    {
        $promises = [];

        // Simulate concurrent requests
        for ($i = 0; $i < 5; $i++) {
            $promises[] = $this->withServerVariables(['REMOTE_ADDR' => "192.168.1.$i"])
                              ->get('/');
        }

        // All should succeed (not rate limited individually)
        foreach ($promises as $response) {
            $this->assertNotEquals(429, $response->getStatusCode());
        }
    }

    /** @test */
    public function it_handles_different_attack_vectors_in_single_request()
    {
        $response = $this->withHeaders([
            'User-Agent' => '<script>alert("header-xss")</script>'
        ])->post('/test?file=../../../etc/passwd', [
            'sql' => "1' OR '1'='1",
            'xss' => '<img src=x onerror=alert("xss")>',
            'cmd' => '; rm -rf /'
        ]);

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Security threat detected', $response->getContent());
    }

    /** @test */
    public function it_logs_security_events()
    {
        // Enable logging
        Config::set('security.logging.enabled', true);
        Config::set('security.logging.sample_rate', 1.0);

        $this->post('/test', [
            'input' => "1' OR '1'='1"
        ]);

        // Check if security event was logged
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $this->assertStringContainsString('security_threat_detected', $logContent);
        }
    }

    /** @test */
    public function it_handles_edge_cases()
    {
        // Empty request
        $response = $this->post('/test', []);
        $this->assertNotEquals(403, $response->getStatusCode());

        // Very long input
        $longInput = str_repeat('A', 10000);
        $response = $this->post('/test', ['input' => $longInput]);
        $this->assertEquals(400, $response->getStatusCode());

        // Unicode characters
        $response = $this->post('/test', ['input' => '안녕하세요']);
        $this->assertNotEquals(403, $response->getStatusCode());

        // Special characters
        $response = $this->post('/test', ['input' => '!@#$%^&*()']);
        $this->assertNotEquals(403, $response->getStatusCode());
    }

    /** @test */
    public function it_maintains_session_across_requests()
    {
        // Start session
        $this->withSession(['test' => 'value']);

        $response = $this->get('/');

        $this->assertNotEquals(429, $response->getStatusCode());
        $this->assertEquals('value', session('test'));
    }
}
