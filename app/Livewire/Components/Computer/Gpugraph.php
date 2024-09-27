<?php

namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\GpuInfo;
use App\Models\GpuTemp;
use App\Models\GpuUsage;

class Gpugraph extends Component
{
    public $device;
    public $gpu_temp_data = [];
    public $gpu_usage_data = [];
    public $timestamps = [];
    public $gpu_id;
    public function mount($device)
    {
        $this->device = $device;
      
        $gpuInfo = GpuInfo::where('device_id', $this->device->id)->first();

        if ($gpuInfo) {
            $gpuId = $gpuInfo->id;
            $this->gpu_id = $gpuId;
            $gpuTempRecords = GpuTemp::where('gpu_id', $gpuId)
                ->whereDate('created_at', now()->toDateString())
                ->get(['temp', 'created_at']);

            $gpuUsageRecords = GpuUsage::where('gpu_id', $gpuId)
                ->whereDate('created_at', now()->toDateString())
                ->get(['usage', 'created_at']);

            $this->gpu_temp_data = $gpuTempRecords->pluck('temp')->toArray();
            $gpu_temp_timestamps = $gpuTempRecords->pluck('created_at')->toArray();

            $this->gpu_usage_data = $gpuUsageRecords->pluck('usage')->toArray();
            $gpu_usage_timestamps = $gpuUsageRecords->pluck('created_at')->toArray();

            $this->timestamps = count($gpu_temp_timestamps) > count($gpu_usage_timestamps) ? $gpu_temp_timestamps : $gpu_usage_timestamps;

            $this->equalizeDataArrays();
        }
    }

    private function equalizeDataArrays()
    {
        $lastTempValue = end($this->gpu_temp_data);
        $lastUsageValue = end($this->gpu_usage_data);

        while (count($this->gpu_temp_data) < count($this->gpu_usage_data)) {
            $this->gpu_temp_data[] = $lastTempValue;
        }

        while (count($this->gpu_usage_data) < count($this->gpu_temp_data)) {
            $this->gpu_usage_data[] = $lastUsageValue;
        }
    }
    public function render()
    {
        return view('livewire.components.computer.gpugraph');
    }
}
