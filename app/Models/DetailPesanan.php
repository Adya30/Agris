<?php

namespace App\Models;

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

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesananId');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produkId');
    }
}