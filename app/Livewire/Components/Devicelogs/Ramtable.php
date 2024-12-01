<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use App\Models\RamUsage;

class Ramtable extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $device;
    public $searchRamLog = ''; // Default empty search value

    protected function getListeners()
    {
        return [
            'search-ramlog' => 'updateSearchRamLog',
        ];
    }

    public function updateSearchRamLog($searchRamLog)
    {
        $this->searchRamLog = $searchRamLog;
        $this->resetPage(); // Reset pagination when the search value changes
    }

    public function mount($device)
    {
        $this->device = $device;
    }

    public function getRamUsageLogsProperty()
    {
        // Fetch RAM usage logs based on search input and device ID
        return RamUsage::whereHas('ramInfo', function ($query) {
            $query->where('device_id', $this->device->id);
        })
        ->when($this->searchRamLog, function ($query) {
            $query->where('usage', 'like', '%' . $this->searchRamLog . '%') // Search by usage
                  ->orWhereHas('ramInfo', function ($query) {
                      $query->where('total_ram', 'like', '%' . $this->searchRamLog . '%'); // Search by total RAM
                  });
        })
        ->orderBy('created_at', 'desc') // Sort by descending order
        ->paginate(10); // Adjust pagination as needed
    }

    public function render()
    {
        return view('livewire.components.devicelogs.ramtable', [
            'ramUsageLogs' => $this->ramUsageLogs,
        ]);
    }
}
