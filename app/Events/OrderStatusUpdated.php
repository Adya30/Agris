<?php

namespace App\Events;

use App\Models\Pesanan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('order.' . $this->pesanan->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'OrderStatusUpdated';
    }
}
