<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mock real-time device metrics
    |--------------------------------------------------------------------------
    |
    | When enabled, the scheduler runs `metrics:mock --once` on the interval
    | below. Requires `php artisan schedule:work` (or a system cron entry).
    | Live graph updates also need your broadcast driver (e.g. Reverb) running.
    |
    */

    'mock_metrics_enabled' => (bool) env('MOCK_METRICS_ENABLED', false),

    /** Allowed: 1, 2, 3, 4, 5, 10, 15, 30 (minutes) */
    'mock_metrics_interval' => max(1, (int) env('MOCK_METRICS_INTERVAL', 5)),

    'mock_metrics_broadcast' => (bool) env('MOCK_METRICS_BROADCAST', true),

    /*
    | Graph events use ShouldBroadcastNow (not queued). Set BROADCAST_CONNECTION
    | to pusher or reverb — not "log" — so Echo receives WebSocket updates.
    */
    'broadcast_connection' => env('BROADCAST_CONNECTION', 'pusher'),

];
