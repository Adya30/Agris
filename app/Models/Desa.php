<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Desa extends Model
{
    use HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'namaDesa',
        'kecamatanId'
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatanId');
    }
}