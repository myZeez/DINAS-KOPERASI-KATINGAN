<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Middleware Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the defensive security
    | middleware. All settings can be overridden via environment variables.
    |
    */

    'enabled' => env('SECURITY_MIDDLEWARE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting Configuration
    |--------------------------------------------------------------------------
    |
    | Token bucket rate limiting with Redis backend
    | All limits are per minute
    |
    */
    'rate_limit' => [
        'global_per_minute' => env('SECURITY_RATE_LIMIT_GLOBAL', 1000),
        'per_ip_per_minute' => env('SECURITY_RATE_LIMIT_IP', 60),
        'per_user_per_minute' => env('SECURITY_RATE_LIMIT_USER', 120),
        'per_route_per_minute' => env('SECURITY_RATE_LIMIT_ROUTE', 200),
        'burst_allowed' => env('SECURITY_RATE_LIMIT_BURST', 20),

        // Route-specific limits
        'routes' => [
            'login' => env('SECURITY_RATE_LIMIT_LOGIN', 5),
            'api.*' => env('SECURITY_RATE_LIMIT_API', 100),
            'admin.*' => env('SECURITY_RATE_LIMIT_ADMIN', 30),
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Web Application Firewall (WAF)
    |--------------------------------------------------------------------------
    |
    | Lightweight WAF with pattern matching and sampling
    |
    */
    'waf' => [
        'enabled' => env('SECURITY_WAF_ENABLED', true),
        'sampling_rate' => env('SECURITY_WAF_SAMPLING_RATE', 0.1),
        'max_content_scan_size' => env('SECURITY_WAF_MAX_SCAN_SIZE', 65536), // 64KB
        'deep_scan_sampling' => env('SECURITY_WAF_DEEP_SAMPLING', 0.01),

        // Rule sets
        'rulesets' => [
            'sqli' => env('SECURITY_WAF_SQLI_ENABLED', true),
            'xss' => env('SECURITY_WAF_XSS_ENABLED', true),
            'lfi' => env('SECURITY_WAF_LFI_ENABLED', true),
            'rce' => env('SECURITY_WAF_RCE_ENABLED', true),
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Input Validation
    |--------------------------------------------------------------------------
    |
    | Request validation and sanitization settings
    |
    */
    'validation' => [
        'max_request_size' => env('SECURITY_MAX_REQUEST_SIZE', 10 * 1024 * 1024), // 10MB
        'max_input_length' => env('SECURITY_MAX_INPUT_LENGTH', 4096),
        'max_file_size' => env('SECURITY_MAX_FILE_SIZE', 5 * 1024 * 1024), // 5MB
        'allowed_file_types' => [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'application/pdf', 'text/plain',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Brute Force Protection
    |--------------------------------------------------------------------------
    |
    | Progressive delays and account lockout
    |
    */
    'brute_force' => [
        'enabled' => env('SECURITY_BRUTE_FORCE_ENABLED', true),
        'max_attempts' => env('SECURITY_BRUTE_FORCE_MAX_ATTEMPTS', 5),
        'lockout_duration' => env('SECURITY_BRUTE_FORCE_LOCKOUT', 900), // 15 minutes
        'progressive_delay' => env('SECURITY_BRUTE_FORCE_DELAY', true),
        'captcha_threshold' => env('SECURITY_BRUTE_FORCE_CAPTCHA', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Headers
    |--------------------------------------------------------------------------
    |
    | HTTP security headers configuration
    |
    */
    'headers' => [
        'hsts' => [
            'enabled' => env('SECURITY_HSTS_ENABLED', true),
            'max_age' => env('SECURITY_HSTS_MAX_AGE', 31536000), // 1 year
            'include_subdomains' => env('SECURITY_HSTS_SUBDOMAINS', true),
        ],
        'csp' => [
            'enabled' => env('SECURITY_CSP_ENABLED', true),
            'policy' => env('SECURITY_CSP_POLICY', "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'"),
        ],
        'frame_options' => env('SECURITY_FRAME_OPTIONS', 'DENY'),
        'content_type_options' => env('SECURITY_CONTENT_TYPE_OPTIONS', 'nosniff'),
        'referrer_policy' => env('SECURITY_REFERRER_POLICY', 'strict-origin-when-cross-origin'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging and Monitoring
    |--------------------------------------------------------------------------
    |
    | Security event logging and metrics
    |
    */
    'logging' => [
        'enabled' => env('SECURITY_LOGGING_ENABLED', true),
        'sample_rate' => env('SECURITY_LOGGING_SAMPLE_RATE', 0.1),
        'log_channel' => env('SECURITY_LOG_CHANNEL', 'daily'),
        'send_to_background' => env('SECURITY_LOGGING_BACKGROUND', true),

        // What to log
        'events' => [
            'rate_limit_exceeded' => true,
            'waf_triggered' => true,
            'brute_force_detected' => true,
            'file_upload_blocked' => true,
            'suspicious_activity' => true,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | Runtime toggles for security features
    |
    */
    'features' => [
        'rate_limiting' => env('SECURITY_FEATURE_RATE_LIMITING', true),
        'waf_protection' => env('SECURITY_FEATURE_WAF', true),
        'brute_force_protection' => env('SECURITY_FEATURE_BRUTE_FORCE', true),
        'file_upload_scanning' => env('SECURITY_FEATURE_FILE_SCAN', true),
        'deep_analysis' => env('SECURITY_FEATURE_DEEP_ANALYSIS', false),
        'threat_intelligence' => env('SECURITY_FEATURE_THREAT_INTEL', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Tuning
    |--------------------------------------------------------------------------
    |
    | Performance-related settings
    |
    */
    'performance' => [
        'max_latency_ms' => env('SECURITY_MAX_LATENCY', 10),
        'cache_ttl' => env('SECURITY_CACHE_TTL', 300), // 5 minutes
        'redis_timeout' => env('SECURITY_REDIS_TIMEOUT', 2), // 2 seconds
        'background_queue' => env('SECURITY_BACKGROUND_QUEUE', 'security'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Hosts and IPs
    |--------------------------------------------------------------------------
    |
    | Whitelist configuration
    |
    */
    'whitelist' => [
        'hosts' => array_filter(explode(',', env('SECURITY_ALLOWED_HOSTS', ''))),
        'ips' => array_filter(explode(',', env('SECURITY_ALLOWED_IPS', ''))),
        'user_agents' => array_filter(explode(',', env('SECURITY_ALLOWED_USER_AGENTS', ''))),
    ],

    /*
    |--------------------------------------------------------------------------
    | Emergency Controls
    |--------------------------------------------------------------------------
    |
    | Emergency bypass and kill switches
    |
    */
    'emergency' => [
        'kill_switch' => env('SECURITY_KILL_SWITCH', false),
        'maintenance_mode_bypass' => env('SECURITY_MAINTENANCE_BYPASS', true),
        'admin_bypass_ips' => array_filter(explode(',', env('SECURITY_ADMIN_BYPASS_IPS', ''))),
    ]
];
