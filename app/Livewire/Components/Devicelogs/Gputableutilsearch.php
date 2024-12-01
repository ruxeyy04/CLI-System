<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;

class Gputableutilsearch extends Component
{
    public $searchValGpuUtil;
    public function updatedSearchValGpuUtil () {
        $this->dispatch('search-gpuutillog', $this->searchValGpuUtil);
    }

    public function render()
    {
        return view('livewire.components.devicelogs.gputableutilsearch');
    }
}
