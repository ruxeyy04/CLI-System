<?php

namespace App\Livewire\Components\Users;

use Livewire\Component;

class UsersTableSearch extends Component
{
    public $searchVal;
    public function updatedSearchVal () {
        $this->dispatch('search-user', $this->searchVal);
    }

    public function render()
    {
        return view('livewire.components.users.users-table-search');
    }
}
