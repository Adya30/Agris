<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Chat extends Model
{
    use HasUlids;

    protected $fillable = [
        'id_pengirim',
        'id_penerima',
        'pesan',
        'foto_chat',
        'status',
        'waktu_chat'
    ];

    protected $casts = [
        'waktu_chat' => 'datetime'
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'id_pengirim');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }
}