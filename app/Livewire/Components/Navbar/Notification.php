<?php

namespace App\Livewire\Components\Navbar;

use App\Models\Notifications;
use Livewire\Component;

class Notification extends Component
{
    public $notificationCount;
    public $notifications;

    /**
     * Fetch notifications when the component is mounted.
     */
    protected function getListeners()
    {
        return [
            'reload-notif' => 'reloadNotif',  // Listen for reload-notif event
        ];
    }

    public function mount()
    {
        // Initial fetch of notifications
        $this->loadNotifications();
    }

    /**
     * Load the latest notifications and notification count.
     */
    public function loadNotifications()
    {
        $this->notificationCount = Notifications::count();

        // Fetch the latest 10 notifications with their associated devices
        $this->notifications = Notifications::with('device')
            ->latest('created_at')
            ->limit(10)
            ->get();
    }

    /**
     * Reload notifications when the 'reload-notif' event is triggered.
     */
    public function reloadNotif()
    {
        // Reload the notifications by calling the loadNotifications method
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.components.navbar.notification', [
            'notificationCount' => $this->notificationCount,
            'notifications' => $this->notifications,
        ]);
    }
}
