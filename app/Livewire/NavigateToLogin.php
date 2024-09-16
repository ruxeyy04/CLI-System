<?php

namespace App\Livewire;

use Livewire\Component;

class NavigateToLogin extends Component
{
    public function login()
    {
        $this->redirectIntended(route('login'), navigate: true);
        return;
    }

    public function render()
    {
        return view('livewire.navigate-to-login');
    }
}
