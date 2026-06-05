<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasUlids, SoftDeletes, HasFactory;

    protected $table = 'produks';

    protected $fillable = [ 'kategoriId', 'namaProduk', 'fotoProduk', 'stok', 'harga', 'deskripsi', ];
    protected $casts = [
        'harga' => 'decimal:2',
        'stok'  => 'integer',
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
