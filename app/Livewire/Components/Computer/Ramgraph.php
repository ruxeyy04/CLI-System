<?php

namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\RamInfo;
use App\Models\RamUsage;
class Ramgraph extends Component
{
    public $device;
    public $ram_usage_data = [];
    public $timestamps = [];

    public function mount($device)
    {
        $this->device = $device;

        $ramInfo = RamInfo::where('device_id', $this->device->id)->first();

        if ($ramInfo) {
            $ramId = $ramInfo->id;

            $ramUsageRecords = RamUsage::where('ram_id', $ramId)
                ->whereDate('created_at', now()->toDateString())
                ->get(['usage', 'created_at']);
            $this->ram_usage_data = $ramUsageRecords->pluck('usage')->toArray();
            $this->timestamps = $ramUsageRecords->pluck('created_at')->toArray();
        }
    }


    public function render()
    {
        return view('livewire.components.computer.ramgraph');
    }
}
