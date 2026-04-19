<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Pembayaran extends Model
{
    use HasUlids;

    protected $fillable = [
        'pesananId',
        'snapToken',
        'transactionId',
        'statusPembayaran',
        'paymentType',
        'totalPembayaran',
        'waktuDibayar',
        'jumlahRefund'
    ];

    protected $casts = [
        'waktuDibayar' => 'datetime',
        'totalPembayaran' => 'decimal:2',
        'jumlahRefund' => 'decimal:2'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesananId');
    }

    public function riwayatTransaksis()
    {
        return $this->hasMany(RiwayatTransaksi::class, 'pembayaranId');
    }
}