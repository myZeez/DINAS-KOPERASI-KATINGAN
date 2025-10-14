# 🚀 Production Deployment Guide

## Langkah-langkah Deployment Security Middleware

### 1. Persiapan Server

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y redis-server nginx php8.1-fpm php8.1-redis clamav clamav-daemon supervisor

# Configure Redis
sudo systemctl enable redis-server
sudo systemctl start redis-server

# Configure ClamAV
sudo freshclam
sudo systemctl enable clamav-daemon
sudo systemctl start clamav-daemon

# Test installations
redis-cli ping  # Should return PONG
clamd --version # Should show ClamAV version
```

### 2. Laravel Configuration

```bash
# Copy security environment file
cp .env.security.example .env.security

# Merge with main environment
cat .env.security >> .env

# Generate application key if needed
php artisan key:generate

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Queue Worker Setup

Buat supervisor config untuk queue workers:

```bash
sudo nano /etc/supervisor/conf.d/laravel-security-worker.conf
```

Isi dengan:
```ini
[program:laravel-security-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/your/project/artisan queue:work redis --queue=security --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/laravel-security-worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-security-worker:*
```

### 4. Nginx Configuration

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your/project/public;
    index index.php index.html;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    limit_req_zone $binary_remote_addr zone=web:10m rate=2r/s;

    # API routes
    location /api/ {
        limit_req zone=api burst=20 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Web routes
    location / {
        limit_req zone=web burst=10 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP handler
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Security
        fastcgi_hide_header X-Powered-By;
        fastcgi_param PHP_VALUE "expose_php=off";
    }

    # Block access to sensitive files
    location ~ /\. {
        deny all;
    }
    
    location ~ /(storage|vendor|tests|database)/ {
        deny all;
    }
}
```

### 5. Redis Optimization

Edit `/etc/redis/redis.conf`:
```conf
# Memory optimization
maxmemory 1gb
maxmemory-policy allkeys-lru

# Performance
tcp-keepalive 60
timeout 300

# Persistence for counters
save 900 1
save 300 10
save 60 10000

# Security
protected-mode yes
bind 127.0.0.1
```

Restart Redis:
```bash
sudo systemctl restart redis-server
```

### 6. Monitoring Setup

Install monitoring tools:
```bash
# Install Prometheus node exporter
wget https://github.com/prometheus/node_exporter/releases/download/v1.3.1/node_exporter-1.3.1.linux-amd64.tar.gz
tar xvfz node_exporter-1.3.1.linux-amd64.tar.gz
sudo mv node_exporter-1.3.1.linux-amd64/node_exporter /usr/local/bin/
sudo useradd -rs /bin/false node_exporter

# Create systemd service
sudo nano /etc/systemd/system/node_exporter.service
```

Service file:
```ini
[Unit]
Description=Node Exporter
After=network.target

[Service]
User=node_exporter
Group=node_exporter
Type=simple
ExecStart=/usr/local/bin/node_exporter

[Install]
WantedBy=multi-user.target
```

Start monitoring:
```bash
sudo systemctl daemon-reload
sudo systemctl enable node_exporter
sudo systemctl start node_exporter
```

### 7. Log Management

Setup log rotation:
```bash
sudo nano /etc/logrotate.d/laravel-security
```

Content:
```
/path/to/your/project/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
    sharedscripts
    postrotate
        /bin/kill -SIGUSR1 `cat /var/run/nginx.pid 2>/dev/null` 2>/dev/null || true
    endscript
}
```

### 8. Security Hardening

```bash
# Firewall rules
sudo ufw enable
sudo ufw allow 22    # SSH
sudo ufw allow 80    # HTTP
sudo ufw allow 443   # HTTPS
sudo ufw deny 6379   # Redis (only local)

# PHP security
sudo nano /etc/php/8.1/fpm/php.ini
```

PHP settings:
```ini
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
max_execution_time = 30
max_input_time = 60
memory_limit = 256M
upload_max_filesize = 5M
post_max_size = 10M
```

### 9. Performance Optimization

```bash
# PHP-FPM tuning
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

FPM settings:
```ini
pm = dynamic
pm.max_children = 20
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 10
pm.max_requests = 500

# Security middleware specific
php_admin_value[memory_limit] = 256M
php_admin_value[max_execution_time] = 30
```

### 10. Health Checks

Create health check script:
```bash
nano /usr/local/bin/security-health-check.sh
```

Content:
```bash
#!/bin/bash

# Check Redis
redis-cli ping > /dev/null
if [ $? -ne 0 ]; then
    echo "ERROR: Redis is down"
    exit 1
fi

# Check queue workers
if ! supervisorctl status laravel-security-worker:* | grep -q RUNNING; then
    echo "ERROR: Security queue workers not running"
    exit 1
fi

# Check Laravel security middleware
response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health)
if [ "$response" != "200" ]; then
    echo "ERROR: Laravel application not responding"
    exit 1
fi

echo "OK: All security components healthy"
```

Make executable and add to cron:
```bash
chmod +x /usr/local/bin/security-health-check.sh

# Add to crontab
crontab -e
# Add line: */5 * * * * /usr/local/bin/security-health-check.sh >> /var/log/security-health.log 2>&1
```

### 11. Testing Production Setup

```bash
# Test rate limiting
for i in {1..10}; do
    curl -w "%{http_code}\n" http://your-domain.com/api/test
done

# Test WAF
curl -X POST http://your-domain.com/api/test \
  -d "malicious=<script>alert('xss')</script>" \
  -w "%{http_code}\n"

# Test file upload security
curl -X POST http://your-domain.com/upload \
  -F "file=@malicious.php" \
  -w "%{http_code}\n"

# Load test
ab -n 1000 -c 10 http://your-domain.com/
```

### 12. Monitoring Dashboard

Setup basic monitoring dashboard di Laravel:
```bash
php artisan make:controller SecurityDashboardController
```

Routes untuk monitoring:
```php
Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/security/dashboard', [SecurityDashboardController::class, 'index']);
    Route::get('/admin/security/metrics', [SecurityDashboardController::class, 'metrics']);
    Route::post('/admin/security/reset-counters', [SecurityDashboardController::class, 'resetCounters']);
});
```

### 13. Backup & Recovery

Setup backup untuk Redis data:
```bash
# Create backup script
nano /usr/local/bin/redis-backup.sh
```

Content:
```bash
#!/bin/bash
BACKUP_DIR="/backup/redis"
DATE=$(date +"%Y%m%d_%H%M%S")

mkdir -p $BACKUP_DIR
redis-cli BGSAVE
cp /var/lib/redis/dump.rdb $BACKUP_DIR/dump_$DATE.rdb

# Keep only last 7 days
find $BACKUP_DIR -name "dump_*.rdb" -mtime +7 -delete
```

### 14. Emergency Procedures

Buat emergency script:
```bash
nano /usr/local/bin/security-emergency.sh
```

Content:
```bash
#!/bin/bash

case "$1" in
    disable)
        echo "Disabling security middleware..."
        sed -i 's/SECURITY_MIDDLEWARE_ENABLED=true/SECURITY_MIDDLEWARE_ENABLED=false/' /path/to/your/project/.env
        php artisan config:cache
        echo "Security middleware disabled"
        ;;
    enable)
        echo "Enabling security middleware..."
        sed -i 's/SECURITY_MIDDLEWARE_ENABLED=false/SECURITY_MIDDLEWARE_ENABLED=true/' /path/to/your/project/.env
        php artisan config:cache
        echo "Security middleware enabled"
        ;;
    block-ip)
        if [ -z "$2" ]; then
            echo "Usage: $0 block-ip <IP_ADDRESS>"
            exit 1
        fi
        echo "Blocking IP: $2"
        redis-cli SETEX "security:blocked:$2" 3600 "emergency_block"
        ;;
    status)
        echo "Security Status:"
        echo "- Middleware: $(grep SECURITY_MIDDLEWARE_ENABLED /path/to/your/project/.env)"
        echo "- Redis: $(redis-cli ping)"
        echo "- Queue workers: $(supervisorctl status laravel-security-worker:*)"
        ;;
    *)
        echo "Usage: $0 {disable|enable|block-ip|status}"
        exit 1
        ;;
esac
```

## 🔧 Troubleshooting Production

### Common Issues

1. **High latency**: Periksa Redis performance dan network latency
2. **False positives**: Review logs dan adjust WAF rules
3. **Queue backlog**: Scale queue workers atau optimize job processing
4. **Memory issues**: Monitor PHP memory usage dan Redis memory

### Performance Monitoring

```bash
# Monitor middleware performance
tail -f storage/logs/laravel.log | grep "middleware.latency"

# Redis performance
redis-cli --latency-history -i 1

# PHP-FPM status
curl http://localhost/fpm-status
```

### Security Incident Response

1. **Immediate**: Use emergency script untuk disable/block
2. **Investigation**: Analyze logs untuk attack patterns
3. **Mitigation**: Update rules atau adjust thresholds
4. **Recovery**: Gradually re-enable protections

Security middleware telah siap untuk production! 🛡️
