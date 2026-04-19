<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Provinsi extends Model
{
    use HasUlids;

    protected $fillable = ['namaProvinsi'];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class, 'provinsiId');
    }
}