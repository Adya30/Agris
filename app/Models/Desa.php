<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Desa extends Model
{
    use HasUlids;

    protected $fillable = ['kecamatanId', 'namaDesa'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatanId');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'desaId');
    }
}