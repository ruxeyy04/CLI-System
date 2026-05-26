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

    /*
    | Daily slots for `device:mock-data` (one CPU/GPU/RAM reading per time).
    */
    'display_timezone' => env('MONITORING_TIMEZONE', 'Asia/Manila'),

    'time_scheduler' => [
        '1' => '07:00:00',
        '2' => '08:30:00',
        '3' => '10:00:00',
        '4' => '11:30:00',
        '5' => '13:00:00',
        '6' => '14:30:00',
        '7' => '16:00:00',
        '8' => '17:30:00',
        '9' => '19:00:00',
    ],

];
