<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class DetailPesanan extends Model
{
    use HasUlids;

    protected $table = 'detail_pesanans';

    protected $fillable = [
        'pesananId',
        'produkId',
        'jumlahPesanan',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
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
    public function scopeProdukId(Builder $query, string $value): Builder
    {
        return $query->where('produkId', $value);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesananId');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produkId');
    }
}
