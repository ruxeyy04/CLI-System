<?php

namespace App\Console\Commands;

use App\Models\ComputerDevice;
use App\Services\DeviceMetricsMockService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateDeviceMockDataCommand extends Command
{
    protected $signature = 'device:mock-data
                            {--device_id= : Device UUID (required)}
                            {--date= : Calendar date (Y-m-d). Defaults to today}
                            {--fresh : Delete existing metrics for that date before inserting}
                            {--no-broadcast : Do not dispatch graph broadcast events}';

    protected $description = 'Generate CPU, GPU, and RAM mock readings at each configured scheduler time for one day';

    public function handle(DeviceMetricsMockService $mockService): int
    {
        $deviceId = $this->option('device_id');

        if (! is_string($deviceId) || trim($deviceId) === '') {
            $this->error('The --device_id option is required.');
            $this->line('Example: php artisan device:mock-data --device_id=BCD6B9F3-82AF-0E4D-0B79-C87F540941C4');

            return self::FAILURE;
        }

        $deviceId = trim($deviceId);
        $device = ComputerDevice::with(['cpuInfo', 'gpuInfo', 'ramInfo'])->find($deviceId);

        if (! $device) {
            $this->error("Device not found: {$deviceId}");

            return self::FAILURE;
        }

        $times = DeviceMetricsMockService::scheduledTimes();

        if ($times === []) {
            $this->error('No scheduler times configured. Check monitoring.time_scheduler in config/monitoring.php.');

            return self::FAILURE;
        }

        $dateOption = $this->option('date');

        try {
            $date = $dateOption
                ? Carbon::createFromFormat('Y-m-d', $dateOption)->startOfDay()
                : now()->startOfDay();
        } catch (\Throwable) {
            $this->error('Invalid --date value. Use format Y-m-d (e.g. 2026-05-26).');

            return self::FAILURE;
        }

        $broadcast = ! $this->option('no-broadcast') && config('monitoring.mock_metrics_broadcast', true);
        $fresh = (bool) $this->option('fresh');

        if ($fresh) {
            $this->line("Clearing existing metrics for {$date->toDateString()}...");
        }

        $result = $mockService->seedScheduledDay($device, $date, $fresh, $broadcast);

        if ($result['skipped']) {
            $this->warn('Device has no CPU, GPU, or RAM hardware records. Seed hardware first (e.g. monitoring:seed).');

            return self::FAILURE;
        }

        $this->info("Inserted {$result['inserted']} scheduled reading(s) for {$device->device_name} on {$date->toDateString()}.");

        $this->table(
            ['Slot', 'Time', 'Recorded at'],
            collect($times)->map(fn (string $time, int $index) => [
                $index + 1,
                $time,
                $result['times'][$index] ?? '—',
            ])->all()
        );

        $this->line('Scheduler times: '.implode(', ', $times));

        return self::SUCCESS;
    }
}
