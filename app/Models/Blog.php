<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasUlids;

    protected $table = 'blogs';

    protected $fillable = [
        'userId',
        'judulBlog',
        'isiBlog',
        'fotoBlog',
        'tanggalBlog',
    ];

    protected $casts = [
        'tanggalBlog' => 'date',
    ];

    public function getFotoUrlAttribute(): ?string
    {
        return storage_url($this->fotoBlog);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
