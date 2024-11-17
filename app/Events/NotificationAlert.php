<?php

namespace App\Events;

use App\Models\ComputerDevice;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deviceId;
    public $message;
    public $title;
    public $devicename;

    /**
     * Create a new event instance.
     */
    public function __construct($deviceId, $message, $title)
    {
        $this->deviceId = $deviceId;
        $this->message = $message;
        $this->title = $title;
        
        // Fetch the device name from the ComputerDevice model using the deviceId
        $device = ComputerDevice::find($deviceId);
        if ($device) {
            $this->devicename = $device->device_name;
        } else {
            $this->devicename = null; // Or handle the case if the device is not found
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('notif-alert'),
        ];
    }

    public function broadcastAs()
    {
        return 'notify.alert';
    }
}
