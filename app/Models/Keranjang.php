<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
    public function scopeProdukId(Builder $query, string $value): Builder
    {
        return $query->where('produkId', $value);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produkId');
    }
}
