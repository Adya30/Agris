<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

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