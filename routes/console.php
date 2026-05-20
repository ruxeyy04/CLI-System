<?php

use App\Services\DeviceMetricsMockService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

$mockInterval = DeviceMetricsMockService::normalizeInterval(
    (int) config('monitoring.mock_metrics_interval', 5)
);

$mockMetricsSchedule = Schedule::command('metrics:mock --once')
    ->when(fn () => config('monitoring.mock_metrics_enabled'))
    ->name('mock-device-metrics')
    ->withoutOverlapping();

match ($mockInterval) {
    1 => $mockMetricsSchedule->everyMinute(),
    2 => $mockMetricsSchedule->everyTwoMinutes(),
    3 => $mockMetricsSchedule->everyThreeMinutes(),
    4 => $mockMetricsSchedule->everyFourMinutes(),
    5 => $mockMetricsSchedule->everyFiveMinutes(),
    10 => $mockMetricsSchedule->everyTenMinutes(),
    15 => $mockMetricsSchedule->everyFifteenMinutes(),
    30 => $mockMetricsSchedule->everyThirtyMinutes(),
    default => $mockMetricsSchedule->everyFiveMinutes(),
};
