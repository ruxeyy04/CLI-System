<?php

namespace App\Livewire\Components\Notifications;

use App\Models\Notifications; 
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Table extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $searchVal = ''; 

    protected function getListeners()
    {
        return [
            'search-notif' => 'searchNotif',
        ];
    }


    public function searchNotif($searchVal)
    {
        $this->searchVal = $searchVal;
        $this->resetPage();
    }


    public function render()
    {
        $notifications = Notifications::with('device')
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->searchVal . '%')
                      ->orWhere('message', 'like', '%' . $this->searchVal . '%')
                      ->orWhereHas('device', function ($deviceQuery) {
                          $deviceQuery->where('device_name', 'like', '%' . $this->searchVal . '%');
                      });
            })
            ->orderBy('created_at', 'DESC') 
            ->paginate(5); 

        return view('livewire.components.notifications.table', [
            'notifications' => $notifications, 
        ]);
    }
}
