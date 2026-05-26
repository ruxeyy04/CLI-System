<?php

namespace App\Livewire\Components\Computer;

use App\Livewire\Concerns\InteractsWithDeviceGraphDateRange;
use App\Models\ComputerDevice;
use App\Models\CpuInfo;
use App\Models\CpuTemp;
use App\Models\CpuUtilization;
use App\Models\GpuInfo;
use App\Models\GpuTemp;
use App\Models\GpuUsage;
use App\Models\RamInfo;
use App\Models\RamUsage;
use App\Support\DeviceChartSeries;
use Carbon\Carbon;
use Livewire\Component;

class DeviceGraphsPanel extends Component
{
    use InteractsWithDeviceGraphDateRange;

    public ComputerDevice $device;

    public string $preset = 'today';

    public string $startDate = '';

    public string $endDate = '';

    public bool $isApplying = false;

    public array $cpu_temp_data = [];

    public array $cpu_util_data = [];

    public array $cpu_timestamps = [];

    public ?int $cpu_id = null;

    public array $gpu_temp_data = [];

    public array $gpu_usage_data = [];

    public array $gpu_timestamps = [];

    public ?int $gpu_id = null;

    public array $ram_usage_data = [];

    public array $ram_timestamps = [];

    public ?int $ram_id = null;

    public function mount(ComputerDevice $device): void
    {
        $this->device = $device;
        $this->initializeGraphDateRange();
        $this->startDate = $this->graphStartDate;
        $this->endDate = $this->graphEndDate;
        $this->loadAllMetrics();
    }

    public function setPreset(string $preset): void
    {
        if (! in_array($preset, ['today', 'yesterday', 'last7', 'custom'], true)) {
            return;
        }

        $this->preset = $preset;

        if ($preset === 'custom') {
            return;
        }

        $this->isApplying = true;

        try {
            $this->applyPreset($preset);
            $this->loadAllMetrics(notifyBrowser: true);
        } finally {
            $this->isApplying = false;
        }
    }

    public function applyCustomRange(): void
    {
        $this->validate([
            'startDate' => ['required', 'date'],
            'endDate' => ['required', 'date', 'after_or_equal:startDate'],
        ], [], [
            'startDate' => 'start date',
            'endDate' => 'end date',
        ]);

        $this->preset = 'custom';
        $this->isApplying = true;

        try {
            $this->graphStartDate = $this->startDate;
            $this->graphEndDate = $this->endDate;
            $this->loadAllMetrics(notifyBrowser: true);
        } finally {
            $this->isApplying = false;
        }
    }

    public function getRangeLabelProperty(): string
    {
        if ($this->startDate === '' || $this->endDate === '') {
            return '';
        }

        if ($this->startDate === $this->endDate) {
            return Carbon::parse($this->startDate)->format('M d, Y');
        }

        return Carbon::parse($this->startDate)->format('M d, Y')
            .' – '
            .Carbon::parse($this->endDate)->format('M d, Y');
    }

    protected function applyPreset(string $preset): void
    {
        [$this->startDate, $this->endDate] = match ($preset) {
            'yesterday' => [
                now()->subDay()->toDateString(),
                now()->subDay()->toDateString(),
            ],
            'last7' => [
                now()->subDays(6)->toDateString(),
                now()->toDateString(),
            ],
            default => [
                now()->toDateString(),
                now()->toDateString(),
            ],
        };

        $this->graphStartDate = $this->startDate;
        $this->graphEndDate = $this->endDate;
    }

    protected function loadAllMetrics(bool $notifyBrowser = false): void
    {
        $this->isLoadingGraph = true;

        try {
            $this->loadCpuMetrics();
            $this->loadGpuMetrics();
            $this->loadRamMetrics();
        } finally {
            $this->isLoadingGraph = false;
        }

        if ($notifyBrowser) {
            $this->dispatchGraphRefresh();
        }
    }

    protected function dispatchGraphRefresh(): void
    {
        $this->dispatch(
            'device-graphs-refreshed',
            cpu: $this->cpuChartPayload(),
            ram: $this->ramChartPayload(),
            gpu: $this->gpuChartPayload(),
        );
    }

    /**
     * @return array{util: list<mixed>, temp: list<mixed>, timestamps: list<string>, startDate: string, endDate: string}
     */
    public function cpuChartPayload(): array
    {
        return [
            'util' => $this->cpu_util_data,
            'temp' => $this->cpu_temp_data,
            'timestamps' => $this->cpu_timestamps,
            'startDate' => $this->graphStartDate,
            'endDate' => $this->graphEndDate,
        ];
    }

    /**
     * @return array{usage: list<mixed>, timestamps: list<string>, startDate: string, endDate: string}
     */
    public function ramChartPayload(): array
    {
        return [
            'usage' => $this->ram_usage_data,
            'timestamps' => $this->ram_timestamps,
            'startDate' => $this->graphStartDate,
            'endDate' => $this->graphEndDate,
        ];
    }

    /**
     * @return array{usage: list<mixed>, temp: list<mixed>, timestamps: list<string>, startDate: string, endDate: string}
     */
    public function gpuChartPayload(): array
    {
        return [
            'usage' => $this->gpu_usage_data,
            'temp' => $this->gpu_temp_data,
            'timestamps' => $this->gpu_timestamps,
            'startDate' => $this->graphStartDate,
            'endDate' => $this->graphEndDate,
        ];
    }

    protected function loadCpuMetrics(): void
    {
        $this->cpu_temp_data = [];
        $this->cpu_util_data = [];
        $this->cpu_timestamps = [];
        $this->cpu_id = null;

        $cpuInfo = CpuInfo::where('device_id', $this->device->id)->first();

        if (! $cpuInfo) {
            return;
        }

        $this->cpu_id = $cpuInfo->id;

        $cpuTempRecords = $this->applyGraphDateBetween(
            CpuTemp::where('cpu_id', $cpuInfo->id)
        )->orderBy('created_at')->get(['temp', 'created_at']);

        $cpuUtilRecords = $this->applyGraphDateBetween(
            CpuUtilization::where('cpu_id', $cpuInfo->id)
        )->orderBy('created_at')->get(['util', 'created_at']);

        $series = DeviceChartSeries::alignDualSeries(
            $cpuTempRecords,
            $cpuUtilRecords,
            'temp',
            'util',
        );

        $this->cpu_timestamps = $series['timestamps'];
        $this->cpu_temp_data = $series['primary'];
        $this->cpu_util_data = $series['secondary'];
    }

    protected function loadGpuMetrics(): void
    {
        $this->gpu_temp_data = [];
        $this->gpu_usage_data = [];
        $this->gpu_timestamps = [];
        $this->gpu_id = null;

        $gpuInfo = GpuInfo::where('device_id', $this->device->id)->first();

        if (! $gpuInfo) {
            return;
        }

        $this->gpu_id = $gpuInfo->id;

        $gpuTempRecords = $this->applyGraphDateBetween(
            GpuTemp::where('gpu_id', $gpuInfo->id)
        )->orderBy('created_at')->get(['temp', 'created_at']);

        $gpuUsageRecords = $this->applyGraphDateBetween(
            GpuUsage::where('gpu_id', $gpuInfo->id)
        )->orderBy('created_at')->get(['usage', 'created_at']);

        $series = DeviceChartSeries::alignDualSeries(
            $gpuTempRecords,
            $gpuUsageRecords,
            'temp',
            'usage',
        );

        $this->gpu_timestamps = $series['timestamps'];
        $this->gpu_temp_data = $series['primary'];
        $this->gpu_usage_data = $series['secondary'];
    }

    protected function loadRamMetrics(): void
    {
        $this->ram_usage_data = [];
        $this->ram_timestamps = [];
        $this->ram_id = null;

        $ramInfo = RamInfo::where('device_id', $this->device->id)->first();

        if (! $ramInfo) {
            return;
        }

        $this->ram_id = $ramInfo->id;

        $ramUsageRecords = $this->applyGraphDateBetween(
            RamUsage::where('ram_id', $ramInfo->id)
        )->orderBy('created_at')->get(['usage', 'created_at']);

        $series = DeviceChartSeries::singleSeries($ramUsageRecords, 'usage');
        $this->ram_usage_data = $series['values'];
        $this->ram_timestamps = $series['timestamps'];
    }

    public function render()
    {
        return view('livewire.components.computer.device-graphs-panel');
    }
}
