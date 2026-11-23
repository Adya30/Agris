<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasUlids;

    protected $table = 'konsultasis';

    protected $fillable = [
        'userId',
        'pertanyaan',
        'jawaban',
        'statusKonsultasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
