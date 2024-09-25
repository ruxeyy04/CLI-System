<?php

use Livewire\Volt\Component;
use App\Models\RamInfo;

new class extends Component {
    public $device; 
    public $ramInfo; 
    public $usedPercentage; // Add a property for the used percentage
    public function getListeners()
    {
        return [
            "echo-private:ram-graph.{$this->device->id},.ram.graph.update" => 'reloadData',
        ];
    }
    public function reloadData()
    {
        $this->ramInfo = RamInfo::where('device_id', $this->device->id)->first(); 
        $this->calculateUsedPercentage(); // Calculate percentage when reloading data
    }

    public function mount($device) 
    {
        $this->device = $device; 
        $this->ramInfo = RamInfo::where('device_id', $this->device->id)->first(); 
        $this->calculateUsedPercentage(); // Calculate percentage on mount
    }

    private function calculateUsedPercentage()
    {
        if ($this->ramInfo && $this->ramInfo->total_ram > 0) {
            $this->usedPercentage = ($this->ramInfo->used / $this->ramInfo->total_ram) * 100;
        } else {
            $this->usedPercentage = 0; // Default to 0 if there's no data or total RAM is zero
        }
    }
};
?>

<div class="mb-5 card card-flush h-md-50 mb-xl-10">
    <!--begin::Header-->
    <div class="pt-5 card-header">
        <!--begin::Title-->
        <div class="card-title d-flex flex-column">
            <!--begin::Info-->
            <div class="d-flex align-items-center">
                <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">RAM</span>
            </div>
            <!--end::Info-->

            <!--begin::Subtitle-->
            <span class="pt-1 text-gray-500 fw-semibold fs-6">Random Access Memory</span>
            <!--end::Subtitle-->
        </div>
        <!--end::Title-->
    </div>
    <!--end::Header-->

    <!--begin::Card body-->
    <div class="pt-2 pb-4 card-body d-flex align-items-center">
        @if ($ramInfo)
            <div class="d-flex flex-column content-justify-center w-100">
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-info me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Total Ram</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $ramInfo->total_ram }} GB</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Used</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $ramInfo->used }} GB ({{ number_format($usedPercentage, 1) }}%)</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Available</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $ramInfo->available }} GB</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Speed</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $ramInfo->speed }} MT/s</div>
                </div>
            </div>
        @else
            <div class="d-flex flex-column content-justify-center w-100">
                <h4>No Data</h4>
            </div>
        @endif
    </div>
</div>
