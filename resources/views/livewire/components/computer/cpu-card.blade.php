<?php

use Livewire\Volt\Component;
use App\Models\CpuInfo;
use Livewire\Attributes\On;
new class extends Component {
    public $device; 
    public $cpuInfo; 


    #[On('echo:device-updates,.patch.saved')]
    public function reloadData()
    {
        $this->cpuInfo = CpuInfo::where('device_id', $this->device->id)->first(); 
    }
    public function mount($device) 
    {
        $this->device = $device; 
        $this->cpuInfo = CpuInfo::where('device_id', $this->device->id)->first(); 
    }


}; ?>

<div class="mb-5 card card-flush h-md-50 mb-xl-10">
    <div class="pt-5 card-header">
        <div class="card-title d-flex flex-column">
            <div class="d-flex align-items-center">
                <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">CPU</span>
            </div>
            <span class="pt-1 text-gray-500 fw-semibold fs-6">Central Processing Unit</span>
        </div>
    </div>
    <div class="pt-2 pb-4 card-body d-flex align-items-center">
        @if ($cpuInfo)
            <div class="d-flex flex-column content-justify-center w-100">
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Brand</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $cpuInfo->brand }}</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-info me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Cores</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $cpuInfo->cores }}</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-info me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Threads</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $cpuInfo->threads }}</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Base Speed</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $cpuInfo->base_speed }} GHz</div>
                </div>
            </div>
        @else
            <div class="d-flex flex-column content-justify-center w-100">
                <h4>No Data</h4>
            </div>
        @endif
    </div>
</div>
