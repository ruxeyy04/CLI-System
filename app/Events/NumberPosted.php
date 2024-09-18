<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NumberPosted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $number;
    public $word;
    public function __construct($number, $word)
    {
        $this->number = $number;
        $this->word = $word;
    }

    public function broadcastOn()
    {
        return new Channel('numbers-channel');
    }

    public function broadcastAs()
    {
        return 'number.posted';
    }
}
