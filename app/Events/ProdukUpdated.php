<?php

namespace App\Events;

use App\Models\Produk;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProdukUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $produk;

    public function __construct(Produk $produk)
    {
        $this->produk = $produk;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('produk-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ProdukUpdated';
    }
}
