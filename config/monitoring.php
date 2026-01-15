<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Error Tracking
    |--------------------------------------------------------------------------
    |
    | Configure error tracking services like Sentry, Bugsnag, etc.
    | Set the service in your .env file.
    |
    */

    'error_tracking' => [
        'enabled' => env('ERROR_TRACKING_ENABLED', false),
        'service' => env('ERROR_TRACKING_SERVICE', 'sentry'),
        'dsn' => env('SENTRY_DSN'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    |
    | Configure performance monitoring services.
    |
    */

    'performance' => [
        'enabled' => env('PERFORMANCE_MONITORING_ENABLED', false),
        'service' => env('PERFORMANCE_MONITORING_SERVICE', 'newrelic'),
        'api_key' => env('NEW_RELIC_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Uptime Monitoring
    |--------------------------------------------------------------------------
    |
    | Configure uptime monitoring services like UptimeRobot, Pingdom, etc.
    | This is typically configured externally, but you can add webhook endpoints here.
    |
    */

    'uptime' => [
        'webhook_enabled' => env('UPTIME_WEBHOOK_ENABLED', false),
        'webhook_url' => env('UPTIME_WEBHOOK_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Aggregation
    |--------------------------------------------------------------------------
    |
    | Configure log aggregation services like Loggly, Papertrail, etc.
    | Configure these in config/logging.php
    |
    */
];

