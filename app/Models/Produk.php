<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Produk extends Model
{
    use HasUlids;

    protected $fillable = [
        'kategoriId',
        'namaProduk',
        'stok',
        'harga',
        'deskripsi'
    ];

    protected $casts = [
        'harga' => 'decimal:2'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategoriId');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'produkId');
    }
}