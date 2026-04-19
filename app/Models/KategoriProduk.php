<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class KategoriProduk extends Model
{
    use HasUlids;

    protected $fillable = ['jenisKategori', 'mutu', 'deskripsi'];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategoriId');
    }
}