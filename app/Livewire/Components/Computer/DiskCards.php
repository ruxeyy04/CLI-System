<?php

namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\DiskInfo;
use Livewire\Attributes\On;
class DiskCards extends Component
{
    public $device;
    public $disks;
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
        // Get disks for the device that are active
        $this->disks = DiskInfo::where('device_id', $device->id)
            ->where('status', 'active')
            ->get();
    }

    public function render()
    {
        return view('livewire.components.computer.disk-cards', [
            'disks' => $this->disks,
            'diskCount' => $this->disks->count()
        ]);
    }
}
