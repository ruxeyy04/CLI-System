<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GpuGraphUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $temp;
    public $usage;
    public $deviceId;
    public function __construct($temp, $usage, $deviceId)
    {
        $this->temp = $temp;
        $this->usage = $usage;
        $this->deviceId = $deviceId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('gpu-graph.' . $this->deviceId),
        ];
    }
    public function broadcastAs()
    {
        return 'gpu.graph.update';
    }
}
