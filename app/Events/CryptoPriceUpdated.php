<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class CryptoPriceUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $crypto;

    public function __construct($crypto)
    {
        $this->crypto = $crypto;
    }

    public function broadcastOn()
    {
        return new Channel('crypto-prices');
    }
}
