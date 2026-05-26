<?php

use App\Models\ComputerDevice;
use App\Models\CpuInfo;
use App\Models\CpuTemp;
use App\Models\CpuUtilization;
use App\Services\DeviceMetricsMockService;
use Illuminate\Support\Str;

test('mock tick appends cpu metrics for a patched device', function () {
    $device = ComputerDevice::create([
        'id' => (string) Str::uuid(),
        'device_name' => 'WS-TEST',
        'serial_number' => 'SN-TEST',
        'patch_id' => 'PATCH-TEST',
        'token' => 'token-test',
        'patched_date' => now(),
    ]);

    $cpu = CpuInfo::create([
        'device_id' => $device->id,
        'brand' => 'Intel Core i5',
        'cores' => '6',
        'threads' => '12',
        'base_speed' => '2.5',
    ]);

    $cpu->cpuTemps()->create(['temp' => 55]);
    $cpu->cpuUtilizations()->create(['util' => 40]);

    $beforeTemps = CpuTemp::count();
    $beforeUtils = CpuUtilization::count();

    $result = app(DeviceMetricsMockService::class)->tickDevice($device, broadcast: false);

    expect($result)->toBeTrue();
    expect(CpuTemp::count())->toBe($beforeTemps + 1);
    expect(CpuUtilization::count())->toBe($beforeUtils + 1);
});

test('normalize interval clamps to allowed values', function () {
    expect(DeviceMetricsMockService::normalizeInterval(5))->toBe(5);
    expect(DeviceMetricsMockService::normalizeInterval(99))->toBe(30);
    expect(DeviceMetricsMockService::normalizeInterval(0))->toBe(1);
});

test('seed scheduled day inserts one reading per scheduler slot', function () {
    $device = ComputerDevice::create([
        'id' => (string) Str::uuid(),
        'device_name' => 'WS-SCHED',
        'serial_number' => 'SN-SCHED',
        'patch_id' => 'PATCH-SCHED',
        'token' => 'token-sched',
        'patched_date' => now(),
    ]);

    $cpu = CpuInfo::create([
        'device_id' => $device->id,
        'brand' => 'Intel Core i5',
        'cores' => '6',
        'threads' => '12',
        'base_speed' => '2.5',
    ]);

    $cpu->cpuTemps()->create(['temp' => 55]);
    $cpu->cpuUtilizations()->create(['util' => 40]);

    $slots = count(DeviceMetricsMockService::scheduledTimes());

    $result = app(DeviceMetricsMockService::class)->seedScheduledDay(
        $device,
        now()->startOfDay(),
        fresh: true,
    );

    expect($result['skipped'])->toBeFalse();
    expect($result['inserted'])->toBe($slots);
    expect(CpuTemp::where('cpu_id', $cpu->id)->count())->toBe($slots);
    expect(CpuUtilization::where('cpu_id', $cpu->id)->count())->toBe($slots);
});

test('device mock data command requires device id', function () {
    $this->artisan('device:mock-data')
        ->assertFailed();
});
