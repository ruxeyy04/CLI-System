<?php

namespace App\Livewire\Components\Device;

use Livewire\Component;

class Search extends Component
{
    public $searchVal;
    public function updatedSearchVal () {
        $this->dispatch('search-user', $this->searchVal);
    }

    public function render()
    {
        return view('livewire.components.device.search');
    }
}
