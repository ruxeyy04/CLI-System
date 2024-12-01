<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;

class Ramtablesearch extends Component
{
    public $searchRamLog;
    public function updatedSearchRamLog () {
        $this->dispatch('search-ramlog', $this->searchRamLog);
    }

    public function render()
    {
        return view('livewire.components.devicelogs.ramtablesearch');
    }
}
