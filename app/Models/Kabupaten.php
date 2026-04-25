<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupatens';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [ 'id','kode', 'provinsiId', 'namaKabupaten' ];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsiId');
    }

    public function kecamatans(): HasMany
    {
        return $this->hasMany(Kecamatan::class, 'kabupatenId');
    }
}
