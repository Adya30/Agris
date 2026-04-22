<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Pesanan extends Model
{
    use HasUlids;

    protected $table = 'pesanans';

    protected $fillable = [
        'userId',
        'tanggal_pesanan',
        'alamat_pengiriman',
        'desaId',
        'status_pesanan',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_pesanan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desaId');
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