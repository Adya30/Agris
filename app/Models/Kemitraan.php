<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
