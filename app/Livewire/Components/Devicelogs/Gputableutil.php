<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\GpuUsage;
use Livewire\WithoutUrlPagination;

class Gputableutil extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $device;
    public $searchValGpuUtil = ''; // Default empty search value

    protected function getListeners()
    {
        return [
            'search-gpuutillog' => 'updateSearchVal',
        ];
    }

    public function updateSearchVal($searchVal)
    {
        $this->searchValGpuUtil = $searchVal;
        $this->resetPage(); // Reset pagination to the first page
    }

    public function mount($device)
    {
        $this->device = $device;
    }

    public function getGpuUtilLogsProperty()
    {
        // Fetch GPU usage logs based on search input and device ID
        return GpuUsage::whereHas('gpuInfo', function ($query) {
            $query->where('device_id', $this->device->id);
        })
        ->when($this->searchValGpuUtil, function ($query) {
            $query->where('usage', 'like', '%' . $this->searchValGpuUtil . '%') // Search by usage
                  ->orWhereHas('gpuInfo', function ($query) {
                      $query->where('brand', 'like', '%' . $this->searchValGpuUtil . '%'); // Search by GPU brand
                  });
        })
        ->orderBy('created_at', 'desc') // Sort by descending order
        ->paginate(10); // Paginate results
    }

    public function render()
    {
        return view('livewire.components.devicelogs.gputableutil', [
            'gpuUtilLogs' => $this->gpuUtilLogs,
        ]);
    }
}
