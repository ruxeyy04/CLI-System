<?php

namespace App\Services;

use App\Events\CpuGraphUpdate;
use App\Events\GpuGraphUpdate;
use App\Events\RamGraphUpdate;
use App\Models\ComputerDevice;
use App\Models\CpuInfo;
use App\Models\GpuInfo;
use App\Models\RamInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class DeviceMetricsMockService
{
    /**
     * @return array{devices: int, skipped: int}
     */
    public function tickAll(bool $broadcast = true, ?string $deviceId = null): array
    {
        $query = ComputerDevice::query()
            ->whereNotNull('patch_id')
            ->with(['cpuInfo', 'gpuInfo', 'ramInfo']);

        if ($deviceId !== null) {
            $query->where('id', $deviceId);
        }

        $devices = $query->get();
        $skipped = 0;

        foreach ($devices as $device) {
            if ($this->tickDevice($device, $broadcast)) {
                continue;
            }

            $skipped++;
        }

        return [
            'devices' => $devices->count() - $skipped,
            'skipped' => $skipped,
        ];
    }

    public function tickDevice(ComputerDevice $device, bool $broadcast = true): bool
    {
        return $this->insertMetricsAt($device, now(), $broadcast, prune: true);
    }

    /**
     * Insert one reading per configured scheduler time for a single calendar day.
     *
     * @return array{inserted: int, skipped: bool, times: list<string>}
     */
    public function seedScheduledDay(
        ComputerDevice $device,
        ?Carbon $date = null,
        bool $fresh = false,
        bool $broadcast = false,
    ): array {
        $date = ($date ?? now())->copy()->startOfDay();

        if ($fresh) {
            $this->clearMetricsForDate($device, $date);
        }

        $device->loadMissing(['cpuInfo', 'gpuInfo', 'ramInfo']);

        if (! $device->cpuInfo && ! $device->gpuInfo && ! $device->ramInfo) {
            return ['inserted' => 0, 'skipped' => true, 'times' => []];
        }

        $inserted = 0;
        $times = [];

        $timezone = config('monitoring.display_timezone', 'Asia/Manila');

        foreach (self::scheduledTimes() as $time) {
            $at = Carbon::parse($date->format('Y-m-d').' '.$time, $timezone);

            if ($this->insertMetricsAt($device, $at, $broadcast, prune: false)) {
                $inserted++;
                $times[] = $at->toDateTimeString();
            }
        }

        return ['inserted' => $inserted, 'skipped' => false, 'times' => $times];
    }

    /**
     * @return list<string> Times in H:i:s order (from config).
     */
    public static function scheduledTimes(): array
    {
        $times = config('monitoring.time_scheduler', []);

        if (! is_array($times) || $times === []) {
            return [];
        }

        ksort($times, SORT_NUMERIC);

        return array_values($times);
    }

    public static function allowedIntervals(): array
    {
        return [1, 2, 3, 4, 5, 10, 15, 30];
    }

    public static function normalizeInterval(int $minutes): int
    {
        if (in_array($minutes, self::allowedIntervals(), true)) {
            return $minutes;
        }

        return max(1, min(30, $minutes));
    }

    public function insertMetricsAt(
        ComputerDevice $device,
        Carbon $at,
        bool $broadcast = false,
        bool $prune = false,
    ): bool {
        $device->loadMissing(['cpuInfo', 'gpuInfo', 'ramInfo']);

        if (! $device->cpuInfo && ! $device->gpuInfo && ! $device->ramInfo) {
            return false;
        }

        $cpuMetrics = $device->cpuInfo
            ? $this->mockCpuMetrics($device->cpuInfo, $at, $prune)
            : null;
        $gpuMetrics = $device->gpuInfo
            ? $this->mockGpuMetrics($device->gpuInfo, $at, $prune)
            : null;
        $ramMetrics = $device->ramInfo
            ? $this->mockRamMetrics($device->ramInfo, $at, $prune)
            : null;

        if ($cpuMetrics && $broadcast) {
            CpuGraphUpdate::dispatch(
                $cpuMetrics['temp'],
                $cpuMetrics['util'],
                $device->id
            );
        }

        if ($gpuMetrics && $broadcast) {
            GpuGraphUpdate::dispatch(
                $gpuMetrics['temp'],
                $gpuMetrics['usage'],
                $device->id
            );
        }

        if ($ramMetrics && $broadcast) {
            RamGraphUpdate::dispatch($ramMetrics['usage'], $device->id);
        }

        return true;
    }

    protected function clearMetricsForDate(ComputerDevice $device, Carbon $date): void
    {
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        if ($cpu = $device->cpuInfo) {
            $cpu->cpuTemps()->whereBetween('created_at', [$start, $end])->delete();
            $cpu->cpuUtilizations()->whereBetween('created_at', [$start, $end])->delete();
        }

        if ($gpu = $device->gpuInfo) {
            $gpu->gpuTemps()->whereBetween('created_at', [$start, $end])->delete();
            $gpu->gpuUsage()->whereBetween('created_at', [$start, $end])->delete();
        }

        if ($ram = $device->ramInfo) {
            $ram->ramUsage()->whereBetween('created_at', [$start, $end])->delete();
        }
    }

    /**
     * @return array{temp: float, util: float}
     */
    protected function mockCpuMetrics(CpuInfo $cpu, Carbon $at, bool $prune): array
    {
        $lastTemp = (float) ($cpu->cpuTemps()->latest()->value('temp') ?? 58);
        $lastUtil = (float) ($cpu->cpuUtilizations()->latest()->value('util') ?? 42);

        $temp = $this->jitter(
            $lastTemp,
            (float) $lastTemp >= DeviceHealthService::CPU_TEMP_THRESHOLD
                ? ['min' => 78, 'max' => 92]
                : ['min' => 45, 'max' => 72]
        );

        $util = $this->jitter(
            $lastUtil,
            (float) $lastUtil >= DeviceHealthService::CPU_UTIL_THRESHOLD
                ? ['min' => 85, 'max' => 98]
                : ['min' => 12, 'max' => 68]
        );

        if ($prune) {
            $this->pruneHistory($cpu->cpuTemps());
            $this->pruneHistory($cpu->cpuUtilizations());
        }

        $this->createAt($cpu->cpuTemps(), ['temp' => $temp], $at);
        $this->createAt($cpu->cpuUtilizations(), ['util' => $util], $at);

        return ['temp' => $temp, 'util' => $util];
    }

    /**
     * @return array{temp: float, usage: float}
     */
    protected function mockGpuMetrics(GpuInfo $gpu, Carbon $at, bool $prune): array
    {
        $lastTemp = (float) ($gpu->gpuTemps()->latest()->value('temp') ?? $gpu->temp ?? 62);
        $lastUsage = (float) ($gpu->gpuUsage()->latest()->value('usage') ?? $gpu->usage ?? 38);

        $temp = $this->jitter(
            $lastTemp,
            $lastTemp >= DeviceHealthService::GPU_TEMP_THRESHOLD
                ? ['min' => 78, 'max' => 90]
                : ['min' => 48, 'max' => 74]
        );

        $usage = $this->jitter(
            $lastUsage,
            $lastUsage >= DeviceHealthService::GPU_USAGE_THRESHOLD
                ? ['min' => 88, 'max' => 98]
                : ['min' => 15, 'max' => 62]
        );

        if ($prune) {
            $this->pruneHistory($gpu->gpuTemps());
            $this->pruneHistory($gpu->gpuUsage());
        }

        $this->createAt($gpu->gpuTemps(), ['temp' => $temp], $at);
        $this->createAt($gpu->gpuUsage(), ['usage' => $usage], $at);

        $gpu->update([
            'temp' => (string) $temp,
            'usage' => (string) $usage,
        ]);

        return ['temp' => $temp, 'usage' => $usage];
    }

    /**
     * @return array{usage: float, used: float, available: float}
     */
    protected function mockRamMetrics(RamInfo $ram, Carbon $at, bool $prune): array
    {
        $total = (float) ($ram->total_ram ?: 16);
        $lastUsage = (float) ($ram->ramUsage()->latest()->value('usage') ?? 55);

        $usage = $this->jitter(
            $lastUsage,
            $lastUsage >= DeviceHealthService::RAM_USAGE_THRESHOLD
                ? ['min' => 86, 'max' => 96]
                : ['min' => 38, 'max' => 72]
        );

        $used = round($total * ($usage / 100), 1);
        $available = round(max(0, $total - $used), 1);

        if ($prune) {
            $this->pruneHistory($ram->ramUsage());
        }

        $this->createAt($ram->ramUsage(), ['usage' => $usage], $at);
        $ram->update([
            'used' => (string) $used,
            'available' => (string) $available,
        ]);

        return ['usage' => $usage, 'used' => $used, 'available' => $available];
    }

    /**
     * @param  HasMany<Model, Model>  $relation
     */
    protected function createAt(HasMany $relation, array $attributes, Carbon $at): Model
    {
        $record = $relation->make($attributes);
        $record->created_at = $at;
        $record->updated_at = $at;
        $record->save();

        return $record;
    }

    /**
     * @param  array{min: float, max: float}  $bounds
     */
    protected function jitter(float $current, array $bounds): float
    {
        $span = $bounds['max'] - $bounds['min'];
        $step = max(0.5, $span * 0.12);
        $delta = (mt_rand(-100, 100) / 100.0) * $step;
        $value = $current + $delta;

        return round(max($bounds['min'], min($bounds['max'], $value)), 1);
    }

    protected function pruneHistory($relation): void
    {
        $relation->newQuery()
            ->where('created_at', '<', now()->startOfDay())
            ->delete();
    }
}
