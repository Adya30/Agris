<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasUlids, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'namaLengkap',
        'email',
        'noTelp',
        'detailAlamat',
        'isAdmin',
        'isActive',
        'desaId'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'isAdmin' => 'boolean',
        'isActive' => 'boolean',
        'email_verified_at' => 'datetime'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desaId');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'userId');
    }

    public function kemitraans()
    {
        return $this->hasMany(Kemitraan::class, 'userId');
    }

    public function konsultasis()
    {
        return $this->hasMany(Konsultasi::class, 'userId');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'userId');
    }

    public function riwayatTransaksis()
    {
        return $this->hasMany(RiwayatTransaksi::class, 'userId');
    }
}