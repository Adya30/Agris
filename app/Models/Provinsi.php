<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsis';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [ 'id', 'kode', 'namaProvinsi'];

    public function kabupatens(): HasMany
    {
        return $this->hasMany(Kabupaten::class, 'provinsiId');
    }
}
