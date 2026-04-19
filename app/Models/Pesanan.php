<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Pesanan extends Model
{
    use HasUlids;

    protected $fillable = [
        'userId',
        'tanggal_pesanan',
        'status_pesanan',
        'deskripsi'
    ];

    protected $casts = [
        'tanggal_pesanan' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'pesananId');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesananId');
    }
}