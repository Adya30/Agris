<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
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
        'waktu_chat',
    ];

    protected $casts = [
        'waktu_chat' => 'datetime',
    ];

    public function getFotoChatUrlAttribute(): ?string
    {
        return storage_url($this->foto_chat);
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengirim');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
