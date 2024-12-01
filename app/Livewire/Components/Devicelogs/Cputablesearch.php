<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;

class Cputablesearch extends Component
{
    public $searchValCpu;
    public function updatedSearchValCpu () {
        $this->dispatch('search-cputemplog', $this->searchValCpu);
    }

    public function render()
    {
        return view('livewire.components.devicelogs.cputablesearch');
    }
}