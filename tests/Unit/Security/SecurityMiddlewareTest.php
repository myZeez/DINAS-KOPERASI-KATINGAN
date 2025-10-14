<?php

namespace Tests\Unit\Security;

use App\Http\Middleware\SecurityMiddleware;
use App\Services\Security\WafRulesEngine;
use App\Services\Security\InputValidationService;
use App\Services\Security\BruteForceProtectionService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Mockery;

class SecurityMiddlewareTest extends TestCase
{
    protected $middleware;
    protected $wafEngine;
    protected $inputValidator;
    protected $bruteForceProtection;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock services
        $this->wafEngine = Mockery::mock(WafRulesEngine::class);
        $this->inputValidator = Mockery::mock(InputValidationService::class);
        $this->bruteForceProtection = Mockery::mock(BruteForceProtectionService::class);

        // Create middleware instance
        $this->middleware = new SecurityMiddleware(
            $this->wafEngine,
            $this->inputValidator,
            $this->bruteForceProtection
        );

        // Configure test environment
        Config::set('security.enabled', true);
        Config::set('security.kill_switch', false);
        Config::set('security.rate_limiting.enabled', true);
        Config::set('security.rate_limiting.requests_per_minute', 60);
        Config::set('security.waf.enabled', true);
        Config::set('security.performance.max_latency_ms', 10);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_allows_valid_requests()
    {
        // Arrange
        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $this->wafEngine->shouldReceive('analyzeRequest')
            ->once()
            ->with($request)
            ->andReturn(['safe' => true, 'threats' => []]);

        $this->inputValidator->shouldReceive('validateRequest')
            ->once()
            ->with($request)
            ->andReturn(['valid' => true, 'errors' => []]);

        $this->bruteForceProtection->shouldReceive('checkRequest')
            ->once()
            ->with($request)
            ->andReturn(['allowed' => true]);

        // Mock Redis for rate limiting
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 1; },
                'expire' => function() { return true; },
                'execute' => function() { return [1, true]; }
            ]);

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }

    /** @test */
    public function it_blocks_rate_limited_requests()
    {
        // Arrange
        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Mock Redis to return high count (over limit)
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 61; }, // Over limit of 60
                'expire' => function() { return true; },
                'execute' => function() { return [61, true]; }
            ]);

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(429, $response->getStatusCode());
        $this->assertStringContainsString('Rate limit exceeded', $response->getContent());
    }

    /** @test */
    public function it_blocks_waf_detected_threats()
    {
        // Arrange
        $request = Request::create('/api/test', 'POST', [
            'input' => "1' OR '1'='1" // SQL injection attempt
        ]);
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Mock rate limiting as OK
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 1; },
                'expire' => function() { return true; },
                'execute' => function() { return [1, true]; }
            ]);

        $this->wafEngine->shouldReceive('analyzeRequest')
            ->once()
            ->with($request)
            ->andReturn([
                'safe' => false,
                'threats' => [
                    ['type' => 'sql_injection', 'severity' => 'high', 'field' => 'input']
                ]
            ]);

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertStringContainsString('Security threat detected', $response->getContent());
    }

    /** @test */
    public function it_blocks_invalid_input()
    {
        // Arrange
        $request = Request::create('/api/test', 'POST', [
            'input' => str_repeat('A', 10000) // Very long input
        ]);
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Mock rate limiting as OK
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 1; },
                'expire' => function() { return true; },
                'execute' => function() { return [1, true]; }
            ]);

        $this->wafEngine->shouldReceive('analyzeRequest')
            ->once()
            ->with($request)
            ->andReturn(['safe' => true, 'threats' => []]);

        $this->inputValidator->shouldReceive('validateRequest')
            ->once()
            ->with($request)
            ->andReturn([
                'valid' => false,
                'errors' => ['input field exceeds maximum length']
            ]);

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertStringContainsString('Invalid input detected', $response->getContent());
    }

    /** @test */
    public function it_blocks_brute_force_attempts()
    {
        // Arrange
        $request = Request::create('/login', 'POST', [
            'email' => 'admin@test.com',
            'password' => 'wrong_password'
        ]);
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Mock rate limiting as OK
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 1; },
                'expire' => function() { return true; },
                'execute' => function() { return [1, true]; }
            ]);

        $this->wafEngine->shouldReceive('analyzeRequest')
            ->once()
            ->with($request)
            ->andReturn(['safe' => true, 'threats' => []]);

        $this->inputValidator->shouldReceive('validateRequest')
            ->once()
            ->with($request)
            ->andReturn(['valid' => true, 'errors' => []]);

        $this->bruteForceProtection->shouldReceive('checkRequest')
            ->once()
            ->with($request)
            ->andReturn([
                'allowed' => false,
                'reason' => 'Too many failed login attempts',
                'retry_after' => 300
            ]);

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(429, $response->getStatusCode());
        $this->assertStringContainsString('Too many failed attempts', $response->getContent());
        $this->assertEquals('300', $response->headers->get('Retry-After'));
    }

    /** @test */
    public function it_adds_security_headers()
    {
        // Arrange
        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $this->wafEngine->shouldReceive('analyzeRequest')
            ->once()
            ->with($request)
            ->andReturn(['safe' => true, 'threats' => []]);

        $this->inputValidator->shouldReceive('validateRequest')
            ->once()
            ->with($request)
            ->andReturn(['valid' => true, 'errors' => []]);

        $this->bruteForceProtection->shouldReceive('checkRequest')
            ->once()
            ->with($request)
            ->andReturn(['allowed' => true]);

        // Mock Redis for rate limiting
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 1; },
                'expire' => function() { return true; },
                'execute' => function() { return [1, true]; }
            ]);

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertNotNull($response->headers->get('X-Frame-Options'));
        $this->assertNotNull($response->headers->get('X-Content-Type-Options'));
        $this->assertNotNull($response->headers->get('X-XSS-Protection'));
        $this->assertNotNull($response->headers->get('Referrer-Policy'));
        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
    }

    /** @test */
    public function it_respects_kill_switch()
    {
        // Arrange
        Config::set('security.kill_switch', true);

        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());

        // Verify no security checks were performed
        $this->wafEngine->shouldNotHaveReceived('analyzeRequest');
        $this->inputValidator->shouldNotHaveReceived('validateRequest');
        $this->bruteForceProtection->shouldNotHaveReceived('checkRequest');
    }

    /** @test */
    public function it_measures_performance()
    {
        // Arrange
        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        $this->wafEngine->shouldReceive('analyzeRequest')
            ->once()
            ->with($request)
            ->andReturn(['safe' => true, 'threats' => []]);

        $this->inputValidator->shouldReceive('validateRequest')
            ->once()
            ->with($request)
            ->andReturn(['valid' => true, 'errors' => []]);

        $this->bruteForceProtection->shouldReceive('checkRequest')
            ->once()
            ->with($request)
            ->andReturn(['allowed' => true]);

        // Mock Redis for rate limiting
        Redis::shouldReceive('pipeline')
            ->once()
            ->andReturn((object)[
                'incr' => function() { return 1; },
                'expire' => function() { return true; },
                'execute' => function() { return [1, true]; }
            ]);

        // Act
        $start = microtime(true);
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });
        $duration = (microtime(true) - $start) * 1000;

        // Assert
        $this->assertLessThan(10, $duration, 'Middleware should complete within 10ms');
        $this->assertNotNull($response->headers->get('X-Security-Latency'));
    }

    /** @test */
    public function it_handles_whitelisted_ips()
    {
        // Arrange
        Config::set('security.whitelist.ips', ['192.168.1.100']);

        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());

        // Verify minimal security checks for whitelisted IPs
        $this->wafEngine->shouldNotHaveReceived('analyzeRequest');
        $this->inputValidator->shouldNotHaveReceived('validateRequest');
        $this->bruteForceProtection->shouldNotHaveReceived('checkRequest');
    }

    /** @test */
    public function it_handles_middleware_disabled()
    {
        // Arrange
        Config::set('security.enabled', false);

        $request = Request::create('/api/test', 'GET');
        $request->server->set('REMOTE_ADDR', '192.168.1.100');

        // Act
        $response = $this->middleware->handle($request, function($req) {
            return new Response('OK', 200);
        });

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());

        // Verify no security checks were performed
        $this->wafEngine->shouldNotHaveReceived('analyzeRequest');
        $this->inputValidator->shouldNotHaveReceived('validateRequest');
        $this->bruteForceProtection->shouldNotHaveReceived('checkRequest');
    }
}
