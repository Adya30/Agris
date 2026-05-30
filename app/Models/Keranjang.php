<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keranjang extends Model
{
    use HasUlids;

    protected $table = 'keranjangs';

    protected $fillable = [
        'userId',
        'produkId',
        'jumlah'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produkId');
    }
}
