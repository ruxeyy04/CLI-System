<?php

namespace App\Console\Commands;

use App\Services\DeviceMetricsMockService;
use Illuminate\Console\Command;

class MockDeviceMetricsCommand extends Command
{
    protected $signature = 'metrics:mock
                            {--once : Push one mock reading for each patched device}
                            {--interval= : Minutes between scheduler runs (1, 2, 3, 4, 5, 10, 15, 30). Defaults to config}
                            {--device= : Only mock a single device UUID}
                            {--no-broadcast : Do not dispatch graph broadcast events}';

    protected $description = 'Mock real-time CPU, RAM, and GPU metrics for patched workstations';

    public function handle(DeviceMetricsMockService $mockService): int
    {
        $broadcast = ! $this->option('no-broadcast') && config('monitoring.mock_metrics_broadcast', true);
        $deviceId = $this->option('device');

        $result = $mockService->tickAll($broadcast, $deviceId);

        if ($result['devices'] === 0) {
            $this->warn('No patched devices with hardware metrics found.');

            return self::SUCCESS;
        }

        $this->info("Mocked metrics for {$result['devices']} device(s).");

        if ($result['skipped'] > 0) {
            $this->line("Skipped {$result['skipped']} device(s) without CPU/RAM/GPU data.");
        }

        if ($broadcast) {
            $connection = config('broadcasting.default');
            $this->line("Graph events broadcast immediately via \"{$connection}\" (not queued).");
            if (in_array($connection, ['log', 'null'], true)) {
                $this->warn('Set BROADCAST_CONNECTION=pusher (or reverb) in .env so Echo receives live updates.');
            }
        }

        return self::SUCCESS;
    }
}
