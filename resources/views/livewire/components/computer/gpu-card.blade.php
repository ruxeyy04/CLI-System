<?php

use Livewire\Volt\Component;
use App\Models\GpuInfo;
use Livewire\Attributes\On;
new class extends Component {
    public $device;
    public $gpuInfo;

    public function getListeners()
    {
        return [
            "echo-private:gpu-graph.{$this->device->id},.gpu.graph.update" => 'reloadData',
        ];
    }
    public function reloadData()
    {
        $this->gpuInfo = GpuInfo::where('device_id', $this->device->id)->first();
    }
    public function mount($device)
    {
        $this->device = $device;
        $this->gpuInfo = GpuInfo::where('device_id', $this->device->id)->first();
    }
}; ?>

<div class="card card-flush h-md-50 mb-xl-10">
    <div class="pt-5 card-header">
        <div class="card-title d-flex flex-column">
            <span class="text-gray-900 fs-2hx fw-bold me-2 lh-1 ls-n2">GPU</span>
            <span class="pt-1 text-gray-500 fw-semibold fs-6">Graphic Processing Unit</span>
        </div>
    </div>
    <div class="pt-2 pb-4 card-body d-flex align-items-center">
        @if ($gpuInfo)
            <div class="d-flex flex-column content-justify-center w-100">
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Brand</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $gpuInfo->brand }}</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-danger me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Temperature</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $gpuInfo->temp }} °C</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-warning me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Usage</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $gpuInfo->usage }} %</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-primary me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">Power</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $gpuInfo->power }}W</div>
                </div>
                <div class="d-flex fs-6 fw-semibold align-items-center">
                    <div class="bullet w-8px h-6px rounded-2 bg-success me-3"></div>
                    <div class="text-gray-500 flex-grow-1 me-4">GPU Memory</div>
                    <div class="text-gray-700 fw-bolder text-xxl-end">{{ $gpuInfo->memory }} MB</div>
                </div>
            </div>
        @else
            <div class="d-flex flex-column content-justify-center w-100">
                <h4>No Data</h4>
            </div>
        @endif

    </div>
</div>
