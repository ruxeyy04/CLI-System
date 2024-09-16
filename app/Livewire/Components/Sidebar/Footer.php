<?php

namespace App\Livewire\Components\Sidebar;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class Footer extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
    public function render()
    {
        return view('livewire.components.sidebar.footer');
    }
}
