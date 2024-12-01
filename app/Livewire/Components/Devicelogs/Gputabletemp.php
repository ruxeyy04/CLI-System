<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\GpuTemp;

class Gputabletemp extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $device;
    public $searchValGpuTemp = ''; // Default empty search value

    protected function getListeners()
    {
        return [
            'search-gpuutiltemp' => 'updateSearchVal',
        ];
    }

    public function updateSearchVal($searchVal)
    {
        $this->searchValGpuTemp = $searchVal;
        $this->resetPage(); // Reset pagination to the first page
    }

    public function mount($device)
    {
        $this->device = $device;
    }

    public function getGpuTempLogsProperty()
    {
        // Fetch GPU temperature logs based on search input and device ID
        return GpuTemp::whereHas('gpuInfo', function ($query) {
            $query->where('device_id', $this->device->id);
        })
        ->when($this->searchValGpuTemp, function ($query) {
            $query->where('temp', 'like', '%' . $this->searchValGpuTemp . '%') // Search by temperature
                  ->orWhereHas('gpuInfo', function ($query) {
                      $query->where('brand', 'like', '%' . $this->searchValGpuTemp . '%'); // Search by GPU brand
                  });
        })
        ->orderBy('created_at', 'desc') // Sort by descending order
        ->paginate(10); // Paginate results
    }

    public function render()
    {
        return view('livewire.components.devicelogs.gputabletemp', [
            'gpuTempLogs' => $this->gpuTempLogs,
        ]);
    }
}
