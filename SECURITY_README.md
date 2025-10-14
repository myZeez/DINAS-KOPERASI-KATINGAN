# 🛡️ Laravel Security Middleware

Middleware defensif optimal untuk Laravel yang memberikan perlindungan multi-layer terhadap serangan web umum dengan overhead minimal (< 10ms target latency).

## 📋 Fitur Keamanan

### ⚡ **Core Protection**
- **Rate Limiting**: Token bucket dengan Redis, per-IP/user/route
- **WAF (Web Application Firewall)**: Pattern matching untuk SQL injection, XSS, LFI, RCE
- **Input Validation**: Schema validation dengan fail-fast approach
- **Brute Force Protection**: Progressive delay + account lockout
- **File Upload Security**: MIME validation, magic bytes, virus scanning
- **Security Headers**: HSTS, CSP, X-Frame-Options, dll.

### 🎯 **Performance Focused**
- Precompiled regex patterns
- Redis pipeline operations
- Sampling untuk expensive checks
- Background processing untuk heavy tasks
- Caching untuk decisions
- Target latency < 10ms p99

### 🔧 **Production Ready**
- Feature flags untuk gradual rollout
- Comprehensive logging dengan sampling
- Metrics collection
- Emergency kill switch
- Configurable thresholds

## 🚀 Quick Start

### 1. Install Dependencies

```bash
# Install Redis untuk rate limiting dan counters
sudo apt-get install redis-server

# Install ClamAV untuk virus scanning (optional)
sudo apt-get install clamav clamav-daemon

# Update virus definitions
sudo freshclam
```

### 2. Configuration

Copy environment variables:
```bash
cp .env.security.example .env.security
cat .env.security >> .env
```

Publish config:
```bash
php artisan vendor:publish --tag=security-config
```

### 3. Register Middleware

Add to `app/Http/Kernel.php`:
```php
protected $middleware = [
    // ... other middleware
    \App\Http\Middleware\SecurityMiddleware::class,
];

// Or register for specific route groups
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        'security',
    ],
    'api' => [
        // ... other middleware
        'security',
    ],
];

protected $routeMiddleware = [
    // ... other middleware
    'security' => \App\Http\Middleware\SecurityMiddleware::class,
];
```

### 4. Setup Queue Worker

```bash
# Start queue worker untuk background security jobs
php artisan queue:work --queue=security
```

### 5. Test Installation

```bash
# Check security status
php artisan security:status

# Test rate limiting
curl -H "X-Forwarded-For: 1.2.3.4" http://localhost/api/test

# Run security tests
php artisan test --filter=Security
```

## ⚙️ Configuration

### Basic Settings

```env
# Enable/disable middleware
SECURITY_MIDDLEWARE_ENABLED=true

# Emergency kill switch
SECURITY_KILL_SWITCH=false

# Rate limits (per minute)
SECURITY_RATE_LIMIT_IP=60
SECURITY_RATE_LIMIT_USER=120

# WAF settings
SECURITY_WAF_ENABLED=true
SECURITY_WAF_SAMPLING_RATE=0.1

# File upload limits
SECURITY_MAX_FILE_SIZE=5242880  # 5MB
```

### Advanced Configuration

See `config/security.php` for all available options:

- **Rate Limiting**: Per-route limits, burst handling
- **WAF Rules**: Enable/disable specific rule sets
- **Logging**: Sample rates, channels
- **Performance**: Latency budgets, cache TTLs
- **Whitelisting**: Trusted IPs, hosts, user agents

## 📊 Monitoring & Metrics

### Built-in Metrics

- `middleware.handled_requests_total`
- `middleware.blocked_requests_total{reason}`
- `middleware.latency_ms_p50,p95,p99`
- `middleware.rate_limit_hits`
- `middleware.waf_triggers`

### Health Checks

```bash
# Security middleware status
php artisan security:status

# Rate limiting stats
php artisan security:rate-limit-stats

# WAF statistics
php artisan security:waf-stats

# Brute force protection stats
php artisan security:brute-force-stats
```

### Log Analysis

Security events are logged to configurable channels:

```bash
# View security events
tail -f storage/logs/security.log

# Alert patterns
grep "CRITICAL" storage/logs/laravel.log
grep "blocked_requests" storage/logs/laravel.log
```

## 🧪 Testing

### Unit Tests

```bash
# Run all security tests
php artisan test --filter=Security

# Specific test suites
php artisan test tests/Unit/Security/
php artisan test tests/Feature/Security/
```

### Load Testing

Test middleware performance under load:

```bash
# Install load testing tools
sudo apt-get install apache2-utils

# Test rate limiting
ab -n 1000 -c 10 http://localhost/api/test

# Test with different IPs
for i in {1..10}; do
  curl -H "X-Forwarded-For: 192.168.1.$i" http://localhost/api/test
done
```

### Security Testing

```bash
# Test SQL injection protection
curl -X POST http://localhost/api/test \
  -d "input=1' OR '1'='1"

# Test XSS protection  
curl -X POST http://localhost/api/test \
  -d "input=<script>alert('xss')</script>"

# Test file upload security
curl -X POST http://localhost/upload \
  -F "file=@malicious.php"
```

## 🚨 Security Alerts

### Alert Channels

Configure alerts in your monitoring system:

```yaml
# Example: Prometheus AlertManager
groups:
- name: security
  rules:
  - alert: HighSecurityEventRate
    expr: rate(blocked_requests_total[5m]) > 10
    annotations:
      summary: "High rate of blocked requests detected"
      
  - alert: SecurityMiddlewareDown
    expr: up{job="laravel-security"} == 0
    annotations:
      summary: "Security middleware is not responding"
```

### Incident Response

When security alerts fire:

1. **Check logs**: `grep "CRITICAL" storage/logs/laravel.log`
2. **Verify attack patterns**: Review blocked request details
3. **Emergency response**: Set `SECURITY_KILL_SWITCH=true` if needed
4. **IP blocking**: Add malicious IPs to firewall
5. **Update rules**: Add new patterns to WAF if required

## 🔧 Troubleshooting

### Common Issues

**High Latency**
```bash
# Check Redis performance
redis-cli --latency

# Reduce sampling rates
SECURITY_WAF_SAMPLING_RATE=0.01
SECURITY_LOGGING_SAMPLE_RATE=0.01
```

**False Positives**
```bash
# Add to whitelist
SECURITY_ALLOWED_IPS="192.168.1.100,10.0.0.50"

# Disable specific rules
SECURITY_WAF_SQLI_ENABLED=false
```

**Memory Issues**
```bash
# Check Redis memory usage
redis-cli info memory

# Clean up old entries
php artisan security:cleanup
```

### Debug Mode

Enable detailed logging:
```env
SECURITY_LOGGING_SAMPLE_RATE=1.0
LOG_LEVEL=debug
```

## 📈 Performance Benchmarks

### Latency Targets

| Component | Target Latency | Typical Latency |
|-----------|----------------|-----------------|
| Rate Limiting | < 3ms | 1-2ms |
| WAF Check (sampled) | < 3ms | 1-3ms |
| Input Validation | < 2ms | 0.5-1ms |
| Header Validation | < 1ms | 0.1-0.5ms |
| **Total Middleware** | **< 10ms** | **3-6ms** |

### Resource Usage

- **Memory**: ~2-5MB per request
- **Redis**: ~1KB per IP/user for counters
- **CPU**: ~2-5% overhead under normal load

## 🛠️ Customization

### Custom WAF Rules

Add your own patterns in `config/security.php`:

```php
'custom_rules' => [
    'my_pattern' => [
        'pattern' => '/custom-threat-pattern/i',
        'severity' => 'high',
        'description' => 'Custom threat detected'
    ]
]
```

### Custom Rate Limits

Per-route configuration:
```php
// In routes/web.php
Route::get('/api/special', function () {
    // ...
})->middleware('security:rate_limit=5'); // 5 requests per minute
```

### Integration with External Services

Extend services for integration:

```php
// Custom threat intelligence
class CustomThreatIntelService extends ThreatIntelligenceService
{
    public function checkIpReputation(string $ip): bool
    {
        // Integrate with VirusTotal, AbuseIPDB, etc.
    }
}
```

## 📚 API Reference

### Artisan Commands

```bash
# Security status and health
php artisan security:status
php artisan security:cleanup
php artisan security:test-rules

# Statistics and monitoring  
php artisan security:stats
php artisan security:export-metrics

# Emergency controls
php artisan security:enable
php artisan security:disable
php artisan security:reset-counters
```

### Service Classes

- `SecurityMiddleware`: Main middleware class
- `WafRulesEngine`: Web application firewall
- `InputValidationService`: Input validation and sanitization
- `BruteForceProtectionService`: Login protection
- `FileUploadSecurityService`: File upload security

## 🚀 Deployment

### Production Checklist

- [ ] Redis configured and monitoring
- [ ] Queue workers running for security jobs
- [ ] Log rotation configured
- [ ] Monitoring and alerting setup
- [ ] Rate limits tuned for your traffic
- [ ] WAF rules tested against your application
- [ ] Emergency procedures documented
- [ ] Security team trained on alerts

### Canary Deployment

1. **Start with sampling**:
   ```env
   SECURITY_WAF_SAMPLING_RATE=0.01
   SECURITY_LOGGING_SAMPLE_RATE=0.1
   ```

2. **Monitor for false positives**
3. **Gradually increase sampling**
4. **Enable all features once stable**

### Scaling Considerations

- **Redis Clustering**: For high-traffic applications
- **Separate Security Queue**: Dedicated workers for security jobs
- **Log Aggregation**: Centralized logging for multiple instances
- **Distributed Caching**: For rate limiting across multiple servers

## 📞 Support

### Getting Help

- **Documentation**: Check this README and inline code comments
- **Logs**: Review `storage/logs/laravel.log` for errors
- **Debug**: Enable debug mode for detailed output
- **Community**: Laravel Security community forums

### Reporting Issues

When reporting security issues:

1. **Don't include sensitive data** in public reports
2. **Provide minimal reproduction steps**
3. **Include configuration and log snippets**
4. **Specify Laravel and PHP versions**

### Security Contact

For security vulnerabilities, please contact: [Your Security Email]

---

## 📄 License

This security middleware is open-sourced software licensed under the [MIT license](LICENSE).

---

⚡ **Built for Production** | 🛡️ **Security First** | 🚀 **Performance Optimized**
