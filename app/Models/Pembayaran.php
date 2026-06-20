<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Pembayaran extends Model
{
    use HasUlids;

    protected $table = 'pembayarans';

    protected $fillable = [
        'pesananId',
        'snapToken',
        'transactionId',
        'statusPembayaran',
        'paymentType',
        'totalPembayaran',
        'waktuDibayar',
        'jumlahRefund',
        'payment_info',
    ];

    protected $casts = [
        'totalPembayaran' => 'decimal:2',
        'jumlahRefund' => 'decimal:2',
        'waktuDibayar' => 'datetime',
    ];

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopePesananId(Builder $query, string $value): Builder
    {
        return $query->where('pesananId', $value);
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeStatusPembayaran(Builder $query, string $value): Builder
    {
        return $query->where('statusPembayaran', $value);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesananId');
    }

    public function riwayatTransaksis()
    {
        return $this->hasMany(RiwayatTransaksi::class, 'pembayaranId');
    }
}
