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
    public $searchVal = ''; // Search input value

    /**
     * Define the event listeners.
     */
    protected function getListeners()
    {
        return [
            'search-notif' => 'searchNotif',
        ];
    }

    /**
     * Method to update the search value when triggered by an event.
     */
    public function searchNotif($searchVal)
    {
        $this->searchVal = $searchVal;
        $this->resetPage(); // Reset to the first page when searching
    }

    /**
     * Render the notifications table.
     */
    public function render()
    {
        // Fetch notifications with filtering by search value (device name, title, or message)
        $notifications = Notifications::with('device')
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->searchVal . '%')
                      ->orWhere('message', 'like', '%' . $this->searchVal . '%')
                      ->orWhereHas('device', function ($deviceQuery) {
                          $deviceQuery->where('device_name', 'like', '%' . $this->searchVal . '%');
                      });
            })
            ->latest()
            ->paginate(5); // Paginate results

        return view('livewire.components.notifications.table', [
            'notifications' => $notifications, // Pass filtered notifications to the view
        ]);
    }
}
