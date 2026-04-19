<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Kecamatan extends Model
{
    use HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'namaKecamatan',
        'kabupatenId'
    ];

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupatenId');
    }

    public function desas()
    {
        return $this->hasMany(Desa::class, 'kecamatanId');
    }
}