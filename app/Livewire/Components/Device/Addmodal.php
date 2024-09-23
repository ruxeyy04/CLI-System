<?php

namespace App\Livewire\Components\Device;

use App\Models\ComputerDevice;
use App\Models\Laboratory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Validate;
class Addmodal extends Component
{
    protected function getListeners()
    {
        return [
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

    public function mount() {
        $user = Auth::user();

        if ($user->role === 'incharge') {
            $this->laboratory = Laboratory::all();
        } elseif ($user->role === 'assistant') {
            $this->laboratory = Laboratory::where('id', $user->laboratory_id)->get();
        }
    }
    public function labSelected($lab_id)
    {
        $this->lab_id = $lab_id;
        $this->resetErrorBag('lab_id');
    }
    public function rules()
    {
        return [
            'device_id' => ['required', 'string', 'max:255', 'unique:computer_devices,id'],
            'device_name' => ['required', 'string', 'max:255', 'unique:' . ComputerDevice::class],
            'serial_number' => ['required', 'string', 'max:255', 'unique:' . ComputerDevice::class],
            'lab_id' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'lab_id.required' => 'You should assign a laboratory room.',
        ];
    }
    public function discardForm(): void
    {
        $this->device_id = '';
        $this->device_name = '';
        $this->lab_id = '';
        $this->serial_number = '';
        $this->resetValidation();
    }
    public function saveDevice()
    {
        $validated = $this->validate();

        $laboratory = ComputerDevice::create([
            'id' => $this->device_id,
            'device_name' => $this->device_name,
            'serial_number' => $this->serial_number,
            'laboratory_id' => $this->lab_id
        ]);


        $this->dispatch('add-device-success');
        $this->discardForm();
    }
    public function render()
    {
        return view('livewire.components.device.addmodal');
    }
}
