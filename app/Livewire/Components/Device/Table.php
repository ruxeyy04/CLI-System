<?php

namespace App\Livewire\Components\Device;

use App\Models\ComputerDevice;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Table extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected function getListeners()
    {
        return [
            'add-device-success' => 'loadDevice',
            'delete-device' => 'deleteDevice',
            'update-device-success' => 'loadDevice',
            'remove-patch' => 'removePatch',
            'search-user' => 'searchUser',
        ];
    }

    protected $paginationTheme = 'bootstrap';
    public $searchVal = '';

    public function deleteDevice($dev_id)
    {
        $dev = ComputerDevice::find($dev_id);
        if ($dev) {
            $dev->delete();
            $this->dispatch('delete-device-alert', status: 'success');
        } else {
            $this->dispatch('delete-device-alert', status: 'fail');
        }
        $this->resetPage();
    }
    public function searchUser($searchVal)
    {
        $this->searchVal = $searchVal;
        $this->resetPage();
    }
    public function removePatch($dev_id)
    {
        $dev = ComputerDevice::find($dev_id);
        if ($dev) {
            $dev->patch_id = null;
            $dev->token = null;
            $dev->patched_date = null;
            $dev->save();

            $this->dispatch('remove-patch-alert', status: 'success');
        } else {
            $this->dispatch('remove-patch-alert', status: 'fail');
        }
        $this->resetPage();
    }

    public function render()
    {
        $searchTerms = explode(' ', $this->searchVal);
    
        $devices = ComputerDevice::with('laboratory');
    
        foreach ($searchTerms as $term) {
            $devices->where(function ($query) use ($term) {
                $query->where('id', 'like', '%' . $term . '%')
                    ->orWhere('device_name', 'like', '%' . $term . '%')
                    ->orWhere('serial_number', 'like', '%' . $term . '%')
                    ->orWhere('patch_id', 'like', '%' . $term . '%')
                    ->orWhereHas('laboratory', function ($query) use ($term) {
                        $query->where('laboratory_name', 'like', '%' . $term . '%');
                    });
            });
        }
    
        $devices = $devices->paginate(5);
    
        return view('livewire.components.device.table', [
            'devices' => $devices
        ]);
    }
    
}
