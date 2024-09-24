<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PatchSaved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $device_id;
    /**
     * Create a new event instance.
     */
    public function __construct($device_id)
    {
        $this->device_id = $device_id;
    }

    public function broadcastOn()
    {
        // Broadcasting on a specific channel, e.g., device-updates
        return new Channel('device-updates');
    }

    /**
     * The data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'device_id' => $this->device_id,
            'message' => "Patch saved successfully for device: {$this->device_id}",
        ];
    }

    /**
     * Name of the broadcast event.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'patch.saved';
    }
}
