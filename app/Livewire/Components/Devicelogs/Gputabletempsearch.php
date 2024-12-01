<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;

class Gputabletempsearch extends Component
{
    public $searchValGpuTemp;
    public function updatedSearchValGpuTemp () {
        $this->dispatch('search-gpuutiltemp', $this->searchValGpuTemp);
    }
    public function render()
    {
        return view('livewire.components.devicelogs.gputabletempsearch');
    }
}
