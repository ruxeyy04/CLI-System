<?php

namespace App\Livewire\Components\Devicelogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CpuTemp;
use Livewire\WithoutUrlPagination;

class Cputable extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $device;
    public $searchVal = ''; // Default empty search value

    protected function getListeners()
    {
        return [
            'search-cputemplog' => 'searchCpuTemp',
        ];
    }

    public function searchCpuTemp($searchValCpu)
    {
        $this->searchVal = $searchValCpu;
        $this->resetPage(); 
    }

    public function mount($device)
    {
        $this->device = $device;
    }

    public function getCputemplogsProperty()
    {
        // Fetch CPU temperature logs based on search input and device ID
        return CpuTemp::whereHas('cpuInfo', function ($query) {
            $query->where('device_id', $this->device->id);
        })
        ->when($this->searchVal, function ($query) {
            $query->where('temp', 'like', '%' . $this->searchVal . '%') // Search by temperature
                  ->orWhereHas('cpuInfo', function ($query) {
                      $query->where('brand', 'like', '%' . $this->searchVal . '%'); // Search by CPU brand
                  });
        })
        ->orderBy('created_at', 'desc') // Sort by descending order
        ->paginate(10); // Paginate results
    }

    public function render()
    {
        return view('livewire.components.devicelogs.cputable', [
            'cputemplogs' => $this->cputemplogs,
        ]);
    }
}
