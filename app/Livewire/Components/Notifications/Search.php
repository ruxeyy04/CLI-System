<?php

namespace App\Livewire\Components\Notifications;

use Livewire\Component;

class Search extends Component
{
    public $searchVal;
    public function updatedSearchVal () {
        $this->dispatch('search-notif', $this->searchVal);
    }

    public function render()
    {
        return view('livewire.components.notifications.search');
    }
}
