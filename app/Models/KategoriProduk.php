<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'kategori_produks';

    protected $fillable = [
        'jenisKategori',
        'mutu',
        'karung',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'kategoriId');
    }
}
