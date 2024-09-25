<?php

namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\DiskInfo; 

class DiskCardSummary extends Component
{
    public $device;
    public $disks = [];
    public function getListeners()
    {
        return [
            "echo-private:disk-update.{$this->device->id},.disk.update" => 'updatedDisks',
        ];
    }
    public function updatedDisks()
    {
        $this->disks = DiskInfo::where('device_id', $this->device->id)
            ->where('status', 'active')
            ->get();
    }
    public function mount($device)
    {
        $this->device = $device;

        $this->disks = DiskInfo::where('device_id', $this->device->id)
            ->where('status', 'active')
            ->get();
    }

    public function render()
    {
        return view('livewire.components.computer.disk-card-summary', [
            'disks' => $this->disks
        ]);
    }
}
