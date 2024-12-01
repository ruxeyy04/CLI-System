<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CpuUtilization;

class Cputableutil extends Component
{
    use WithPagination;

    public $device;
    public $searchValUtil = ''; // Default search value

    protected function getListeners()
    {
        return [
            'search-cpuutillog' => 'searchUtil',
        ];
    }

    public function searchUtil($searchVal)
    {
        $this->searchValUtil = $searchVal;
        $this->resetPage(); // Reset pagination to the first page
    }

    public function mount($device)
    {
        $this->device = $device;
    }

    public function getCpuUtilLogsProperty()
    {
        // Fetch CPU utilization logs based on search input and device ID
        return CpuUtilization::whereHas('cpuInfo', function ($query) {
            $query->where('device_id', $this->device->id);
        })
        ->when($this->searchValUtil, function ($query) {
            $query->where('util', 'like', '%' . $this->searchValUtil . '%') // Search by utilization
                  ->orWhereHas('cpuInfo', function ($query) {
                      $query->where('brand', 'like', '%' . $this->searchValUtil . '%'); // Search by CPU brand
                  });
        })
        ->orderBy('created_at', 'desc') // Sort by descending order
        ->paginate(10); // Paginate results
    }

    public function render()
    {
        return view('livewire.components.devicelogs.cputableutil', [
            'cpuUtilLogs' => $this->cpuUtilLogs,
        ]);
    }
}
