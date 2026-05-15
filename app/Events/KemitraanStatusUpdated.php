<?php

namespace App\Events;

use App\Models\Kemitraan;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KemitraanStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $kemitraan;

    public function __construct(Kemitraan $kemitraan)
    {
        $this->kemitraan = $kemitraan;
    }

    public function broadcastOn(): array
    {
        // Broadcast ke channel khusus user tersebut
        return [
            new PrivateChannel('kemitraan.' . $this->kemitraan->userId),
            new PrivateChannel('admin.kemitraan') 
        ];
    }
}
