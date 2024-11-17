<?php

namespace App\Livewire\Components\Sidebar;

use App\Models\ComputerDevice;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use hisorange\BrowserDetect\Parser as Browser;

class Layout extends Component
{
    protected function getListeners()
    {
        return [
            'add-device-success' => 'reloadSidebar',
            'update-device-success' => 'reloadSidebar',
            'add-laboratory-success' => 'reloadSidebar',
            'update-sidebar' => 'reloadSidebar'
        ];
    }
    public $devices; 
    public function mount()
    {
        $this->devices = $this->loadDevices(); 
    }
    
    public function reloadSidebar()
    {
        $this->devices = $this->loadDevices(); 
    }
    
    protected function loadDevices()
    {
        return ComputerDevice::with([
            'cpuInfo.cpuUtilizations',
            'cpuInfo.cpuTemps',
            'gpuInfo.gpuUsage',
            'gpuInfo.gpuTemps',
            'ramInfo.ramUsage',
        ])->get();
    }
    
    
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
    
        return view('livewire.components.sidebar.layout', [
            'laboratories' => \App\Models\Laboratory::all(), 
            'devices' => $this->devices, 
        ]);
    }
    
}
