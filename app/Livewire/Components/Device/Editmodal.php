<?php

namespace App\Livewire\Components\Device;

use App\Models\ComputerDevice;
use App\Models\Laboratory;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Editmodal extends Component
{
    protected function getListeners()
    {
        return [
            'edit_device' => 'loadDevice',
            'lab_select' => 'labSelected',
        ];
    }
    #[Validate]
    public $device_id;
    #[Validate]
    public $device_name;
    #[Validate]
    public $serial_number;
    #[Validate]
    public $lab_id;

    public $laboratory;
    public function mount()
    {
        $user = Auth::user();

        if ($user->role === 'incharge') {
            $this->laboratory = Laboratory::all();
        } elseif ($user->role === 'assistant') {
            $this->laboratory = Laboratory::where('id', $user->laboratory_id)->get();
        }
    }
    public function rules()
    {
        return [
            'device_id' => [
                'required',
                'string',
                'max:255',
                'exists:computer_devices,id', 
                'unique:computer_devices,id,' . $this->device_id,
            ],
            'device_name' => [
                'required',
                'string',
                'max:255',
                'unique:computer_devices,device_name,' . $this->device_id,
            ],
            'serial_number' => [
                'required',
                'string',
                'max:255',
                'unique:computer_devices,serial_number,' . $this->device_id,
            ],
            'lab_id' => ['required'],
        ];
    }
    public function labSelected($lab_id)
    {
        $this->lab_id = $lab_id;
        $this->resetErrorBag('lab_id');
    }
    public function loadDevice($dev_id)
    {
        $dev = ComputerDevice::findOrFail($dev_id);
        $this->device_id = $dev->id;
        $this->device_name = $dev->device_name;
        $this->serial_number = $dev->serial_number;
        $this->lab_id = $dev->laboratory_id;
        $this->dispatch('edit_device_done', select2: $dev->laboratory_id);
    }
    public function updateDevice()
    {
        $validated = $this->validate();
        $dev = ComputerDevice::find($this->device_id);
        if ($dev) {
            $dev->device_name = $this->device_name;
            $dev->serial_number = $this->serial_number;
            $dev->laboratory_id = $this->lab_id;
        } else {
            $dev = new ComputerDevice([
                'id' => $this->device_id,
                'device_name' => $this->device_name,
                'serial_number' => $this->serial_number,
                'laboratory_id' => $this->lab_id
            ]);
        }

        // Save the laboratory
        $dev->save();
        $this->dispatch('update-device-success');
    }

    public function render()
    {
        return view('livewire.components.device.editmodal');
    }
}
