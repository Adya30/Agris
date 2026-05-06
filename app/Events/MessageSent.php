<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $isDelete;

    public function __construct($chat, $isDelete = false)
    {
        $this->chat = $chat;
        $this->isDelete = $isDelete;
    }

    public function broadcastOn(): array
    {
        $penerima = is_array($this->chat) ? $this->chat['id_penerima'] : $this->chat->id_penerima;
        $pengirim = is_array($this->chat) ? $this->chat['id_pengirim'] : $this->chat->id_pengirim;

        if ($penerima === 'GLOBAL') {
            return [new Channel('chat.global')];
        }

        return [
            new PrivateChannel('chat.' . $penerima),
            new PrivateChannel('chat.' . $pengirim),
        ];
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'chat' => $this->chat,
            'is_delete' => $this->isDelete
        ];
    }
}
