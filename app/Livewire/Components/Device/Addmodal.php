<?php

namespace App\Livewire\Components\Device;

use App\Models\Laboratory;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Addmodal extends Component
{
    public $device_id;
    public $device_name;
    public $laboratory;

    public function mount() {
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
            'device_id' => ['required', 'string', 'max:255', 'unique:' . Laboratory::class],
            'device_name' => ['required', 'integer', 'max:255'],
        ];
    }
    public function discardForm(): void
    {
        $this->device_id = '';
        $this->device_name = '';
        $this->laboratory = '';
        $this->resetValidation();
    }
    public function saveDevice()
    {
        $validated = $this->validate();

        $laboratory = Laboratory::create([
            'laboratory_name' => $this->laboratory_name,
            'capacity' => $this->capacity,
        ]);


        $this->dispatch('add-device-success');
        $this->discardForm();
    }
    public function render()
    {
        return view('livewire.components.device.addmodal');
    }
}
