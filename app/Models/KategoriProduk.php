<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class KategoriProduk extends Model
{
    use HasUlids;

    protected $table = 'kategori_produks';

    protected $fillable = [
        'jenisKategori',
        'mutu',
        'karung'
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategoriId');
    }
}
