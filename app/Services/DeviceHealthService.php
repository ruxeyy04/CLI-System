<?php

namespace App\Services;

use App\Models\ComputerDevice;
use App\Models\InputDevice;

class DeviceHealthService
{
    public const CPU_TEMP_THRESHOLD = 80;

    public const CPU_UTIL_THRESHOLD = 90;

    public const GPU_TEMP_THRESHOLD = 80;

    public const GPU_USAGE_THRESHOLD = 90;

    public const RAM_USAGE_THRESHOLD = 85;

    /**
     * @return 'healthy'|'unhealthy'|'unknown'
     */
    public function getDeviceStatus(ComputerDevice $device): string
    {
        if (!$device->patch_id) {
            return 'unknown';
        }

        $hasMetrics = false;
        $unhealthy = false;

        $cpuInfo = $device->cpuInfo;
        if ($cpuInfo) {
            $lastTemp = $cpuInfo->cpuTemps()->latest()->first();
            $lastUtil = $cpuInfo->cpuUtilizations()->latest()->first();

            if ($lastTemp) {
                $hasMetrics = true;
                if ((float) $lastTemp->temp >= self::CPU_TEMP_THRESHOLD) {
                    $unhealthy = true;
                }
            }

            if ($lastUtil) {
                $hasMetrics = true;
                if ((float) $lastUtil->util >= self::CPU_UTIL_THRESHOLD) {
                    $unhealthy = true;
                }
            }
        }

        $gpuInfo = $device->gpuInfo;
        if ($gpuInfo) {
            $lastTemp = $gpuInfo->gpuTemps()->latest()->first();
            $lastUsage = $gpuInfo->gpuUsage()->latest()->first();

            if ($lastTemp) {
                $hasMetrics = true;
                if ((float) $lastTemp->temp >= self::GPU_TEMP_THRESHOLD) {
                    $unhealthy = true;
                }
            }

            if ($lastUsage) {
                $hasMetrics = true;
                if ((float) $lastUsage->usage >= self::GPU_USAGE_THRESHOLD) {
                    $unhealthy = true;
                }
            }
        }

        $ramInfo = $device->ramInfo;
        if ($ramInfo) {
            $lastUsage = $ramInfo->ramUsage()->latest()->first();

            if ($lastUsage) {
                $hasMetrics = true;
                if ((float) $lastUsage->usage >= self::RAM_USAGE_THRESHOLD) {
                    $unhealthy = true;
                }
            }
        }

        if (!$hasMetrics) {
            return 'unknown';
        }

        return $unhealthy ? 'unhealthy' : 'healthy';
    }

    public function isDeviceHealthy(ComputerDevice $device): bool
    {
        return $this->getDeviceStatus($device) === 'healthy';
    }

    /**
     * @return 'healthy'|'unhealthy'|'unknown'
     */
    public function getLabStatus(iterable $devices): string
    {
        $hasDevices = false;
        $hasHealthy = false;
        $hasUnhealthy = false;
        $hasUnknown = false;

        foreach ($devices as $device) {
            $hasDevices = true;
            $status = $this->getDeviceStatus($device);

            match ($status) {
                'healthy' => $hasHealthy = true,
                'unhealthy' => $hasUnhealthy = true,
                default => $hasUnknown = true,
            };
        }

        if (!$hasDevices) {
            return 'unknown';
        }

        if ($hasUnhealthy) {
            return 'unhealthy';
        }

        if ($hasHealthy && !$hasUnknown) {
            return 'healthy';
        }

        if ($hasHealthy) {
            return 'healthy';
        }

        return 'unknown';
    }

    /**
     * @return array<string, mixed>
     */
    public function getDeviceSnapshot(ComputerDevice $device): array
    {
        $device->loadMissing(['laboratory', 'cpuInfo', 'gpuInfo', 'ramInfo', 'diskInfo', 'inputDevices']);

        $status = $this->getDeviceStatus($device);
        $cpu = ['has_data' => false];
        $gpu = ['has_data' => false];
        $ram = ['has_data' => false];
        $issues = [];

        if ($cpuInfo = $device->cpuInfo) {
            $lastTemp = $cpuInfo->cpuTemps()->latest()->first();
            $lastUtil = $cpuInfo->cpuUtilizations()->latest()->first();

            $cpu = [
                'has_data' => true,
                'brand' => $cpuInfo->brand,
                'cores' => $cpuInfo->cores,
                'threads' => $cpuInfo->threads,
                'base_speed' => $cpuInfo->base_speed,
                'temperature' => $lastTemp ? (float) $lastTemp->temp : null,
                'utilization' => $lastUtil ? (float) $lastUtil->util : null,
            ];

            if ($lastTemp && (float) $lastTemp->temp >= self::CPU_TEMP_THRESHOLD) {
                $issues[] = 'CPU temperature above '.self::CPU_TEMP_THRESHOLD.'°C';
            }
            if ($lastUtil && (float) $lastUtil->util >= self::CPU_UTIL_THRESHOLD) {
                $issues[] = 'CPU utilization above '.self::CPU_UTIL_THRESHOLD.'%';
            }
        }

        if ($gpuInfo = $device->gpuInfo) {
            $lastTemp = $gpuInfo->gpuTemps()->latest()->first();
            $lastUsage = $gpuInfo->gpuUsage()->latest()->first();

            $gpu = [
                'has_data' => true,
                'brand' => $gpuInfo->brand,
                'temperature' => $lastTemp ? (float) $lastTemp->temp : ($gpuInfo->temp !== null ? (float) $gpuInfo->temp : null),
                'usage' => $lastUsage ? (float) $lastUsage->usage : ($gpuInfo->usage !== null ? (float) $gpuInfo->usage : null),
                'memory' => $gpuInfo->memory,
                'power' => $gpuInfo->power,
            ];

            $gpuTemp = $gpu['temperature'];
            $gpuUsage = $gpu['usage'];

            if ($gpuTemp !== null && $gpuTemp >= self::GPU_TEMP_THRESHOLD) {
                $issues[] = 'GPU temperature above '.self::GPU_TEMP_THRESHOLD.'°C';
            }
            if ($gpuUsage !== null && $gpuUsage >= self::GPU_USAGE_THRESHOLD) {
                $issues[] = 'GPU usage above '.self::GPU_USAGE_THRESHOLD.'%';
            }
        }

        if ($ramInfo = $device->ramInfo) {
            $lastUsage = $ramInfo->ramUsage()->latest()->first();
            $usagePercent = null;

            if ($lastUsage) {
                $usagePercent = (float) $lastUsage->usage;
            } elseif ($ramInfo->total_ram > 0) {
                $usagePercent = round(((float) $ramInfo->used / (float) $ramInfo->total_ram) * 100, 1);
            }

            $ram = [
                'has_data' => true,
                'total_gb' => $ramInfo->total_ram,
                'used_gb' => $ramInfo->used,
                'available_gb' => $ramInfo->available,
                'speed' => $ramInfo->speed,
                'usage_percent' => $usagePercent,
            ];

            if ($usagePercent !== null && $usagePercent >= self::RAM_USAGE_THRESHOLD) {
                $issues[] = 'RAM usage above '.self::RAM_USAGE_THRESHOLD.'%';
            }
        }

        $keyboards = $device->inputDevices
            ->where('device_type', 'keyboard')
            ->whereNull('removed_on')
            ->map(fn (InputDevice $input) => $this->mapInputDevice($input))
            ->values()
            ->all();

        $mice = $device->inputDevices
            ->where('device_type', 'pointing_device')
            ->whereNull('removed_on')
            ->map(fn (InputDevice $input) => $this->mapInputDevice($input))
            ->values()
            ->all();

        $disks = $device->diskInfo
            ->whereNull('ejected_on')
            ->map(fn ($disk) => [
                'volume_label' => $disk->volume_label,
                'mountpoint' => $disk->mountpoint,
                'total' => $disk->total,
                'used' => $disk->used,
                'free' => $disk->free,
                'health' => $disk->health,
                'temperature' => $disk->temperature,
                'drive_type' => $disk->drive_type,
                'model' => $disk->model,
                'serial_number' => $disk->serial_number,
                'status' => $disk->status,
            ])
            ->values()
            ->all();

        return [
            'id' => $device->id,
            'name' => $device->device_name,
            'serial_number' => $device->serial_number,
            'laboratory' => $device->laboratory?->laboratory_name ?? 'Not assigned',
            'patch_status' => $device->patch_id ? 'Patched' : 'Not patched',
            'patched_date' => $device->patched_date?->format('d M Y, h:i a'),
            'added_on' => $device->created_at?->format('d M Y, h:i a'),
            'status' => $status,
            'issues' => $issues,
            'cpu' => $cpu,
            'gpu' => $gpu,
            'ram' => $ram,
            'keyboards' => $keyboards,
            'mice' => $mice,
            'disks' => $disks,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function mapInputDevice(InputDevice $input): array
    {
        return [
            'brand' => $input->brand,
            'model' => $input->model,
            'serial_number' => $input->serial_number,
            'manufacturer' => $input->manufacturer,
            'description' => $input->description,
            'input_id' => $input->input_id,
            'input_status' => $input->input_status,
            'physical_status' => $input->physical_status,
            'note' => $input->note,
            'note_added' => $input->note_added?->format('d M Y, h:i a'),
            'creation_class_name' => $input->creation_class_name,
        ];
    }
}
