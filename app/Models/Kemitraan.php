<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Kemitraan extends Model
{
    use HasUlids;

    protected $table = 'kemitraans';

    protected $fillable = [
        'userId',
        'tanggalPengajuan',
        'statusPengajuan',
        'fileKemitraan',
    ];

    protected $casts = [
        'tanggalPengajuan' => 'date',
    ];

    public function scopeUserId(Builder $query, string $value): Builder
    {
        return $query->where('userId', $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
