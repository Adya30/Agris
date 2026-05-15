<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class KemitraanUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $status;

    public function __construct($id, $status)
    {
        $this->id = $id;
        $this->status = $status;
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
