<?php

namespace App\Livewire\Components\Computer;

use App\Livewire\Concerns\InteractsWithDeviceGraphDateRange;
use App\Models\ComputerDevice;
use App\Models\CpuInfo;
use App\Models\CpuTemp;
use App\Models\CpuUtilization;
use App\Support\DeviceChartSeries;
use Livewire\Attributes\On;
use Livewire\Component;

class Cpugraph extends Component
{
    use InteractsWithDeviceGraphDateRange;

    public ComputerDevice $device;

    public array $cpu_temp_data = [];

    public array $cpu_util_data = [];

    public array $timestamps = [];

    public ?int $cpu_id = null;

    public function mount(ComputerDevice $device): void
    {
        $this->device = $device;
        $this->initializeGraphDateRange();
        $this->loadMetrics();
    }

    #[On('graph-date-range-changed')]
    public function onGraphDateRangeChanged(string $startDate, string $endDate): void
    {
        $this->graphStartDate = $startDate;
        $this->graphEndDate = $endDate;
        $this->loadMetrics();
    }

    protected function loadMetrics(): void
    {
        $this->isLoadingGraph = true;

        try {
            $this->cpu_temp_data = [];
            $this->cpu_util_data = [];
            $this->timestamps = [];
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

            $this->timestamps = $series['timestamps'];
            $this->cpu_temp_data = $series['primary'];
            $this->cpu_util_data = $series['secondary'];
        } finally {
            $this->isLoadingGraph = false;
        }
    }

    public function render()
    {
        return view('livewire.components.computer.cpugraph');
    }
}
