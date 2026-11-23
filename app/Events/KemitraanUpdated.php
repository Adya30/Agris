<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KemitraanUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;

    public $status;

    public $userId;

    public function __construct($id, $status, $userId = null)
    {
        $this->id = $id;
        $this->status = $status;
        $this->userId = $userId;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('kemitraan-status'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'KemitraanUpdated';
    }
}
