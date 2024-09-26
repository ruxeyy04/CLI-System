<?php 
namespace App\Livewire\Components\Computer;

use Livewire\Component;
use App\Models\InputDevice;

class Modal extends Component
{
    public $inputid;
    public $type;
    public $inputDevice = []; // Initialize as an empty array

    protected function getListeners() {
        return [
            'update-data-modal-input' => 'openModal'
        ];
    }

    public function openModal($id, $type) {
        $this->resetValidation();
        $this->inputid = $id;
        $this->type = $type;

        // Fetch the input device, default to empty array if not found
        $device = InputDevice::find($id);

        if (!$device) {
            // Handle the case when no device is found
            $this->dispatch('error', message: 'Device not found');
        } else {
            // Populate $inputDevice with the found device's attributes
            $this->inputDevice = $device->toArray(); 
            $this->dispatch('open-update-modal');
        }
    }

    public function save() {
        if (!empty($this->inputDevice)) {
            $this->validate([
                'inputDevice.brand' => 'required_if:type,keyboard-data,mouse-data',
                'inputDevice.model' => 'required_if:type,keyboard-data,mouse-data',
                'inputDevice.serial_number' => 'required_if:type,keyboard-data,mouse-data',
                'inputDevice.manufacturer' => 'required_if:type,keyboard-data,mouse-data',
                'inputDevice.physical_status' => 'required_if:type,keyboard-status,mouse-status',
            ], $this->messages());

            // Update the InputDevice model
            $device = InputDevice::find($this->inputid);
            if ($device) {
                if (!empty($this->inputDevice['note'])) {
                    $this->inputDevice['note_added'] = now(); 
                } else {
                    $this->inputDevice['note'] = null; 
                    $this->inputDevice['note_added'] = null; 
                }
                $device->update($this->inputDevice); // Save the updated data
                $this->dispatch('close-modal');
            } else {
                $this->dispatch('error', message: 'Unable to save device. Device not found.');
            }
        } else {
            $this->dispatch('error', message: 'No data to save.');
        }
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    protected function messages()
    {
        return [
            'inputDevice.brand.required_if' => 'The brand field is required when updating device data.',
            'inputDevice.model.required_if' => 'The model field is required when updating device data.',
            'inputDevice.serial_number.required_if' => 'The serial number field is required when updating device data.',
            'inputDevice.manufacturer.required_if' => 'The manufacturer field is required when updating device data.',
            'inputDevice.physical_status.required_if' => 'The physical status field is required when updating device status.',
            'inputDevice.note.required_if' => 'The note field is required when updating device notes.',
        ];
    }

    public function discardForm(): void {
        $this->inputDevice = [];
        $this->type = '';
        $this->resetValidation();
    }

    public function render() {
        return view('livewire.components.computer.modal');
    }
}
