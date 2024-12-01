<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;

class Cputableutilsearch extends Component
{
    public $searchValCpuUtil;
    public function updatedSearchValCpuUtil () {
        $this->dispatch('search-cpuutillog', $this->searchValCpuUtil);
    }

    public function render()
    {
        return view('livewire.components.devicelogs.cputableutilsearch');
    }
}