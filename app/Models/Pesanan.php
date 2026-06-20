<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeUserId(Builder $query, string $value): Builder
    {
        return $query->where('userId', $value);
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeStatusPesanan(Builder $query, string $value): Builder
    {
        return $query->where('status_pesanan', $value);
    }

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

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesananId');
    }

    public function getStatusAttribute()
    {
        return $this->status_pesanan;
    }

    public function getTotalHargaAttribute()
    {
        if ($this->pembayaran) {
            return $this->pembayaran->totalPembayaran;
        }
        return $this->detailPesanan->sum('subtotal');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pesananId');
    }
}
