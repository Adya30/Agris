<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class RiwayatTransaksi extends Model
{
    use HasUlids;

    protected $table = 'riwayat_transaksis';

    protected $fillable = [
        'userId',
        'pembayaranId',
        'kategoriRiwayat',
        'tanggalRiwayat',
        'deskripsi',
    ];

    protected $casts = [
        'tanggalRiwayat' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'pembayaranId');
    }
}