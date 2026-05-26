<?php

namespace App\Livewire\Components\Computer;

use App\Livewire\Concerns\InteractsWithDeviceGraphDateRange;
use App\Models\ComputerDevice;
use App\Models\RamInfo;
use App\Models\RamUsage;
use App\Support\DeviceChartSeries;
use Livewire\Attributes\On;
use Livewire\Component;

class Ramgraph extends Component
{
    use InteractsWithDeviceGraphDateRange;

    public ComputerDevice $device;

    public array $ram_usage_data = [];

    public array $timestamps = [];

    public ?int $ram_id = null;

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
            $this->ram_usage_data = [];
            $this->timestamps = [];
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
            $this->timestamps = $series['timestamps'];
        } finally {
            $this->isLoadingGraph = false;
        }
    }

    public function render()
    {
        return view('livewire.components.computer.ramgraph');
    }
}
