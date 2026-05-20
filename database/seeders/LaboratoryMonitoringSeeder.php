<?php

namespace Database\Seeders;

use App\Models\ComputerDevice;
use App\Models\CpuInfo;
use App\Models\DiskInfo;
use App\Models\InputDevice;
use App\Models\Laboratory;
use App\Models\RamInfo;
use App\Models\GpuInfo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LaboratoryMonitoringSeeder extends Seeder
{
    /**
     * Demo labs and workstations with green (healthy) and red (unhealthy) metrics.
     *
     * Run: php artisan db:seed --class=LaboratoryMonitoringSeeder
     */
    public function run(): void
    {
        $labs = [
            ['name' => 'HF-201', 'capacity' => 30],
            ['name' => 'HF-202', 'capacity' => 40],
            ['name' => 'HF-204', 'capacity' => 35],
            ['name' => 'HF-304', 'capacity' => 25],
        ];

        $labIds = [];
        foreach ($labs as $lab) {
            $record = Laboratory::firstOrCreate(
                ['laboratory_name' => $lab['name']],
                ['capacity' => $lab['capacity']]
            );
            $labIds[$lab['name']] = $record->id;
        }

        $workstations = [
            // HF-201 — 5 workstations (1 red)
            ['lab' => 'HF-201', 'name' => 'WS-01', 'healthy' => true],
            ['lab' => 'HF-201', 'name' => 'WS-02', 'healthy' => true],
            ['lab' => 'HF-201', 'name' => 'WS-03', 'healthy' => false, 'issue' => 'cpu_temp'],
            ['lab' => 'HF-201', 'name' => 'WS-04', 'healthy' => true],
            ['lab' => 'HF-201', 'name' => 'WS-05', 'healthy' => true],

            // HF-202 — 5 workstations (2 red)
            ['lab' => 'HF-202', 'name' => 'WS-06', 'healthy' => true],
            ['lab' => 'HF-202', 'name' => 'WS-07', 'healthy' => false, 'issue' => 'ram'],
            ['lab' => 'HF-202', 'name' => 'WS-08', 'healthy' => true],
            ['lab' => 'HF-202', 'name' => 'WS-09', 'healthy' => false, 'issue' => 'gpu_temp'],
            ['lab' => 'HF-202', 'name' => 'WS-10', 'healthy' => true],

            // HF-204 — 5 workstations (1 red)
            ['lab' => 'HF-204', 'name' => 'WS-11', 'healthy' => true],
            ['lab' => 'HF-204', 'name' => 'WS-12', 'healthy' => false, 'issue' => 'cpu_util'],
            ['lab' => 'HF-204', 'name' => 'WS-13', 'healthy' => true],
            ['lab' => 'HF-204', 'name' => 'WS-14', 'healthy' => true],
            ['lab' => 'HF-204', 'name' => 'WS-15', 'healthy' => true],

            // HF-304 — 5 workstations (1 red)
            ['lab' => 'HF-304', 'name' => 'WS-16', 'healthy' => true],
            ['lab' => 'HF-304', 'name' => 'WS-17', 'healthy' => true],
            ['lab' => 'HF-304', 'name' => 'WS-18', 'healthy' => false, 'issue' => 'gpu_usage'],
            ['lab' => 'HF-304', 'name' => 'WS-19', 'healthy' => true],
            ['lab' => 'HF-304', 'name' => 'WS-20', 'healthy' => true],
        ];

        $patchedAt = Carbon::parse('2024-11-22 19:00:00');

        foreach ($workstations as $index => $ws) {
            $slug = Str::lower(str_replace('-', '', $ws['name']));
            $labId = $labIds[$ws['lab']];

            $existing = ComputerDevice::where('device_name', $ws['name'])
                ->where('laboratory_id', $labId)
                ->first();

            $attributes = [
                'serial_number' => 'SN-'.strtoupper($slug),
                'patch_id' => 'PATCH-'.strtoupper($slug),
                'token' => 'token-'.Str::random(16),
                'patched_date' => $patchedAt->copy()->addMinutes($index),
            ];

            if (!$existing) {
                $attributes['id'] = (string) Str::uuid();
            }

            $device = ComputerDevice::updateOrCreate(
                ['device_name' => $ws['name'], 'laboratory_id' => $labId],
                $attributes
            );

            $metrics = $this->metricsForWorkstation($ws['healthy'], $ws['issue'] ?? null);
            $this->seedHardwareMetrics($device, $metrics);
            $this->seedPeripherals($device, $ws['name']);
            $this->seedStorage($device, $ws['name']);
        }

        $this->command?->info('Seeded 4 laboratories (HF-201, HF-202, HF-204, HF-304) with 20 workstations.');
        $this->command?->info('Health mix: 15 green (healthy), 5 red (unhealthy).');
    }

    /**
     * @return array{cpu_temp: float, cpu_util: float, gpu_temp: float, gpu_usage: float, ram_usage: float, ram_used: float, ram_total: float}
     */
    protected function metricsForWorkstation(bool $healthy, ?string $issue): array
    {
        $base = [
            'cpu_temp' => 58.0,
            'cpu_util' => 42.0,
            'gpu_temp' => 62.0,
            'gpu_usage' => 38.0,
            'ram_usage' => 55.0,
            'ram_used' => 8.8,
            'ram_total' => 16.0,
        ];

        if ($healthy) {
            return $base;
        }

        return match ($issue) {
            'cpu_temp' => array_merge($base, ['cpu_temp' => 86.0]),
            'cpu_util' => array_merge($base, ['cpu_util' => 94.0]),
            'gpu_temp' => array_merge($base, ['gpu_temp' => 84.0]),
            'gpu_usage' => array_merge($base, ['gpu_usage' => 93.0]),
            'ram' => array_merge($base, ['ram_usage' => 91.0, 'ram_used' => 14.6]),
            default => array_merge($base, ['cpu_temp' => 82.0]),
        };
    }

    /**
     * @param  array{cpu_temp: float, cpu_util: float, gpu_temp: float, gpu_usage: float, ram_usage: float, ram_used: float, ram_total: float}  $metrics
     */
    protected function seedHardwareMetrics(ComputerDevice $device, array $metrics): void
    {
        $cpu = CpuInfo::updateOrCreate(
            ['device_id' => $device->id],
            [
                'brand' => 'Intel Core i5-12400',
                'arch' => 'x86_64',
                'bits' => '64',
                'cores' => '6',
                'threads' => '12',
                'frequency' => '2.5',
                'base_speed' => '2.5',
            ]
        );

        $cpu->cpuTemps()->delete();
        $cpu->cpuUtilizations()->delete();
        $cpu->cpuTemps()->create(['temp' => $metrics['cpu_temp']]);
        $cpu->cpuUtilizations()->create(['util' => $metrics['cpu_util']]);

        $ramTotal = $metrics['ram_total'];
        $ramUsed = $metrics['ram_used'];
        $ram = RamInfo::updateOrCreate(
            ['device_id' => $device->id],
            [
                'total_ram' => (string) $ramTotal,
                'used' => (string) $ramUsed,
                'available' => (string) round($ramTotal - $ramUsed, 1),
                'speed' => '3200',
            ]
        );

        $ram->ramUsage()->delete();
        $ram->ramUsage()->create(['usage' => $metrics['ram_usage']]);

        $gpu = GpuInfo::updateOrCreate(
            ['device_id' => $device->id],
            [
                'brand' => 'NVIDIA GeForce GTX 1660 SUPER',
                'temp' => (string) $metrics['gpu_temp'],
                'usage' => (string) $metrics['gpu_usage'],
                'memory' => '6144',
                'power' => '85',
            ]
        );

        $gpu->gpuTemps()->delete();
        $gpu->gpuUsage()->delete();
        $gpu->gpuTemps()->create(['temp' => $metrics['gpu_temp']]);
        $gpu->gpuUsage()->create(['usage' => $metrics['gpu_usage']]);
    }

    protected function seedPeripherals(ComputerDevice $device, string $workstationName): void
    {
        InputDevice::where('device_id', $device->id)->delete();

        InputDevice::create([
            'device_id' => $device->id,
            'brand' => 'Logitech',
            'model' => 'K120',
            'serial_number' => 'KB-'.$workstationName,
            'manufacturer' => 'Logitech',
            'description' => 'Standard USB keyboard',
            'input_id' => 'keyboard-'.Str::slug($workstationName),
            'input_status' => 'Connected',
            'physical_status' => 'Good',
            'note' => null,
            'creation_class_name' => 'Keyboard',
            'device_type' => 'keyboard',
        ]);

        InputDevice::create([
            'device_id' => $device->id,
            'brand' => 'Logitech',
            'model' => 'M100',
            'serial_number' => 'MS-'.$workstationName,
            'manufacturer' => 'Logitech',
            'description' => 'Standard USB mouse',
            'input_id' => 'mouse-'.Str::slug($workstationName),
            'input_status' => 'Connected',
            'physical_status' => 'Good',
            'note' => null,
            'creation_class_name' => 'Mouse',
            'device_type' => 'pointing_device',
        ]);
    }

    protected function seedStorage(ComputerDevice $device, string $workstationName): void
    {
        DiskInfo::where('device_id', $device->id)->delete();

        DiskInfo::create([
            'device_id' => $device->id,
            'volume_label' => 'System',
            'mountpoint' => 'C:',
            'total' => 512,
            'used' => 210,
            'free' => 302,
            'health' => 'Good',
            'temperature' => '38',
            'drive_type' => 'SSD',
            'model' => 'Samsung 970 EVO',
            'serial_number' => 'DISK-C-'.$workstationName,
            'status' => 'Online',
        ]);

        DiskInfo::create([
            'device_id' => $device->id,
            'volume_label' => 'Data',
            'mountpoint' => 'D:',
            'total' => 1024,
            'used' => 640,
            'free' => 384,
            'health' => 'Good',
            'temperature' => '35',
            'drive_type' => 'HDD',
            'model' => 'WD Blue',
            'serial_number' => 'DISK-D-'.$workstationName,
            'status' => 'Online',
        ]);
    }
}
