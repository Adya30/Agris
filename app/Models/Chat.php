<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'waktu_chat' => 'datetime',
    ];

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengirim');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }
}
