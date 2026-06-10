<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Refund extends Model
{
    use HasUlids;

    protected $table = 'refunds';

    protected $fillable = [
        'pesananId',
        'detailPesananId',
        'jumlah',
        'nominal',
        'alasan',
        'foto_bukti',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'nominal' => 'decimal:2',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesananId');
    }

    public function detailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detailPesananId');
    }
}
