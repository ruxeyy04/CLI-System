<?php

namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\InputDevice;

class InputDevices extends Component
{

    public $keyboards = [];
    public $pointingDevices = [];
    public $device;
    public function getListeners()
    {
        return [
            "echo-private:input-update.{$this->device->id},.input.update" => 'loadInputDevices',
            'close-modal' => 'loadInputDevices'
        ];
    }
    public function mount($device)
    {
        $this->device->$device;
        $this->loadInputDevices();
    }

    public function loadInputDevices()
    {
        $this->keyboards = InputDevice::where('device_type', 'keyboard')->where('device_id', $this->device->id)
            ->whereNull('removed_on')
            ->get();

        $this->pointingDevices = InputDevice::where('device_type', 'pointing_device')->where('device_id', $this->device->id)
            ->whereNull('removed_on')
            ->get();
    }

    public function render()
    {
        return view('livewire.components.computer.input-devices', [
            'keyboards' => $this->keyboards,
            'pointingDevices' => $this->pointingDevices,
        ]);
    }
}
