<?php

namespace App\Livewire\Components\Sidebar;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use hisorange\BrowserDetect\Parser as Browser;
class Layout extends Component
{
    public function render()
    {
        $sessionId = session()->getId();
        DB::table('sessions')
            ->where('id', $sessionId)
            ->update([
                'devicefamily' => Browser::deviceFamily(),
                'devicemodel' => Browser::deviceModel(),
                'browsername' => Browser::browserName(),
                'platformname' => Browser::platformName(),
            ]);
        return view('livewire.components.sidebar.layout');
    }
}
