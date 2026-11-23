<?php

namespace App\Models;

use App\Events\ProdukUpdated;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'produks';

    protected $fillable = ['kategoriId', 'namaProduk', 'fotoProduk', 'stok', 'harga', 'deskripsi'];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(function ($produk) {
            broadcast(new ProdukUpdated($produk));
        });

        static::deleted(function ($produk) {
            broadcast(new ProdukUpdated($produk));
        });

        static::restored(function ($produk) {
            broadcast(new ProdukUpdated($produk));
        });
    }

    public function getFotoUrlAttribute(): ?string
    {
        return storage_url($this->fotoProduk);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriProduk::class, 'kategoriId');
    }

    public function detailPesanans()
    {
        return $this->hasMany(DetailPesanan::class, 'produkId');
    }
}
