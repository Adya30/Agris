<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Kabupaten extends Model
{
    use HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'namaKabupaten',
        'provinsiId'
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsiId');
    }

    public function kecamatans()
    {
        return $this->hasMany(Kecamatan::class, 'kabupatenId');
    }
}