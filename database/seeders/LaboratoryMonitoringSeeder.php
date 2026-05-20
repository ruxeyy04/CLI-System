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
use InvalidArgumentException;

class LaboratoryMonitoringSeeder extends Seeder
{
    public const DEFAULT_WORKSTATIONS_PER_LAB = 5;

    /** @var list<string>|null null = seed all laboratories */
    protected ?array $selectedLaboratories = null;

    protected int $workstationsPerLab = self::DEFAULT_WORKSTATIONS_PER_LAB;

    protected ?int $greenPerLab = null;

    protected ?int $redPerLab = null;

    /** @var list<string> */
    protected array $redIssueTypes = ['cpu_temp', 'ram', 'gpu_temp', 'cpu_util', 'gpu_usage'];

    /**
     * @param  list<string>  $names
     */
    public function setLaboratories(array $names): self
    {
        $this->selectedLaboratories = self::parseLaboratoryNames(implode(',', $names));

        return $this;
    }

    /**
     * Configure workstations per laboratory and health mix (green + red must equal total when both set).
     */
    public function setWorkstationCounts(int $perLab, ?int $green = null, ?int $red = null): self
    {
        $this->workstationsPerLab = self::validateWorkstationCounts($perLab, $green, $red);
        [$this->greenPerLab, $this->redPerLab] = self::resolveHealthCounts($perLab, $green, $red);

        return $this;
    }

    public static function validateWorkstationCounts(int $perLab, ?int $green, ?int $red): int
    {
        if ($perLab < 1) {
            throw new InvalidArgumentException('Workstations per lab must be at least 1.');
        }

        if ($perLab > 99) {
            throw new InvalidArgumentException('Workstations per lab cannot exceed 99 (WS-01 to WS-99).');
        }

        if ($green !== null && $green < 0) {
            throw new InvalidArgumentException('Green count cannot be negative.');
        }

        if ($red !== null && $red < 0) {
            throw new InvalidArgumentException('Red count cannot be negative.');
        }

        if ($green !== null && $red !== null && ($green + $red) !== $perLab) {
            throw new InvalidArgumentException("Green ({$green}) + red ({$red}) must equal workstations per lab ({$perLab}).");
        }

        if ($green !== null && $green > $perLab) {
            throw new InvalidArgumentException('Green count cannot exceed workstations per lab.');
        }

        if ($red !== null && $red > $perLab) {
            throw new InvalidArgumentException('Red count cannot exceed workstations per lab.');
        }

        return $perLab;
    }

    /**
     * @return array{0: int, 1: int} [green, red]
     */
    public static function resolveHealthCounts(int $perLab, ?int $green, ?int $red): array
    {
        if ($green !== null && $red !== null) {
            return [$green, $red];
        }

        if ($green !== null) {
            return [$green, $perLab - $green];
        }

        if ($red !== null) {
            return [$perLab - $red, $red];
        }

        $redCount = $perLab <= 1 ? 0 : max(1, (int) round($perLab * 0.2));

        return [$perLab - $redCount, $redCount];
    }

    /**
     * @return array<string, array{capacity: int}>
     */
    public static function laboratoryCatalog(): array
    {
        return [
            'HF-201' => ['capacity' => 30],
            'HF-202' => ['capacity' => 40],
            'HF-204' => ['capacity' => 35],
            'HF-304' => ['capacity' => 25],
        ];
    }

    /**
     * @return list<string>
     */
    public static function parseLaboratoryNames(string $input): array
    {
        $catalog = self::laboratoryCatalog();
        $aliases = self::laboratoryAliases();
        $names = [];

        foreach (explode(',', $input) as $part) {
            $part = trim($part);

            if ($part === '') {
                continue;
            }

            $normalized = $aliases[strtoupper(str_replace(' ', '', $part))] ?? null;

            if (!$normalized) {
                $candidate = strtoupper($part);
                if (!str_starts_with($candidate, 'HF-')) {
                    $candidate = 'HF-'.$candidate;
                }
                $normalized = array_key_exists($candidate, $catalog) ? $candidate : null;
            }

            if (!$normalized) {
                throw new InvalidArgumentException("Unknown laboratory: {$part}");
            }

            $names[] = $normalized;
        }

        $names = array_values(array_unique($names));

        if ($names === []) {
            throw new InvalidArgumentException('No valid laboratory names were provided.');
        }

        return $names;
    }

    /**
     * Run: php artisan monitoring:seed --labs=HF-202 --workstations=10 --green=8 --red=2
     */
    public function run(): void
    {
        if ($this->greenPerLab === null || $this->redPerLab === null) {
            [$this->greenPerLab, $this->redPerLab] = self::resolveHealthCounts(
                $this->workstationsPerLab,
                $this->greenPerLab,
                $this->redPerLab
            );
        }

        $catalog = self::laboratoryCatalog();
        $labsToSeed = $this->resolveLaboratoriesToSeed($catalog);

        $labIds = [];
        foreach ($labsToSeed as $name => $lab) {
            $record = Laboratory::firstOrCreate(
                ['laboratory_name' => $name],
                ['capacity' => $lab['capacity']]
            );
            $labIds[$name] = $record->id;
        }

        $workstations = $this->workstationsForLabs(array_keys($labsToSeed));
        $patchedAt = Carbon::parse('2024-11-22 19:00:00');
        $healthyCount = 0;
        $unhealthyCount = 0;

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

            $ws['healthy'] ? $healthyCount++ : $unhealthyCount++;
        }

        $labList = implode(', ', array_keys($labsToSeed));
        $labCount = count($labsToSeed);
        $this->command?->info("Seeded laboratories: {$labList}");
        $this->command?->info("Per lab: {$this->workstationsPerLab} workstations ({$this->greenPerLab} green, {$this->redPerLab} red).");
        $this->command?->info("Totals: {$healthyCount} green, {$unhealthyCount} red (".count($workstations)." across {$labCount} ".($labCount === 1 ? 'lab' : 'labs').').');
    }

    /**
     * @param  array<string, array{capacity: int}>  $catalog
     * @return array<string, array{capacity: int}>
     */
    protected function resolveLaboratoriesToSeed(array $catalog): array
    {
        if ($this->selectedLaboratories === null) {
            return $catalog;
        }

        $selected = [];
        foreach ($this->selectedLaboratories as $name) {
            $selected[$name] = $catalog[$name];
        }

        return $selected;
    }

    /**
     * @param  list<string>  $labNames
     * @return list<array{lab: string, name: string, healthy: bool, issue?: string}>
     */
    protected function workstationsForLabs(array $labNames): array
    {
        [$greenCount, $redCount] = self::resolveHealthCounts(
            $this->workstationsPerLab,
            $this->greenPerLab,
            $this->redPerLab
        );

        $workstations = [];

        foreach ($labNames as $lab) {
            $redIssueIndex = 0;

            for ($i = 1; $i <= $this->workstationsPerLab; $i++) {
                $isHealthy = $i <= $greenCount;
                $entry = [
                    'lab' => $lab,
                    'name' => 'WS-'.str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'healthy' => $isHealthy,
                ];

                if (!$isHealthy) {
                    $entry['issue'] = $this->redIssueTypes[$redIssueIndex % count($this->redIssueTypes)];
                    $redIssueIndex++;
                }

                $workstations[] = $entry;
            }
        }

        return $workstations;
    }

    /**
     * @return array<string, string>
     */
    protected static function laboratoryAliases(): array
    {
        return [
            'HF201' => 'HF-201',
            'HF202' => 'HF-202',
            'HF204' => 'HF-204',
            'HF304' => 'HF-304',
            'HF-201' => 'HF-201',
            'HF-202' => 'HF-202',
            'HF-204' => 'HF-204',
            'HF-304' => 'HF-304',
        ];
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
