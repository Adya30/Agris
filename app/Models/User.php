<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUlids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'username',
        'password',
        'namaLengkap',
        'email',
        'noTelp',
        'detailAlamat',
        'isAdmin',
        'isActive',
        'desaId',

        // Google OAuth
        'google_id',
        'google_token',
        'google_refresh_token',

        // 2FA
        'two_factor_type',
        'two_factor_code',
        'two_factor_expires_at',
        'two_factor_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
        'google_token',
        'google_refresh_token',
    ];

    protected function casts(): array
    {
        return [
            'password'              => 'hashed',
            'isAdmin'               => 'boolean',
            'isActive'              => 'boolean',
            'two_factor_verified'   => 'boolean',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI WILAYAH
    |--------------------------------------------------------------------------
    */

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desaId');
    }

    public function getKecamatanAttribute()
    {
        return $this->desa?->kecamatan;
    }

    public function getKabupatenAttribute()
    {
        return $this->desa?->kecamatan?->kabupaten;
    }

    public function getProvinsiAttribute()
    {
        return $this->desa?->kecamatan?->kabupaten?->provinsi;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER ALAMAT LENGKAP
    |--------------------------------------------------------------------------
    */

    public function getAlamatLengkapAttribute(): string
    {
        return implode(', ', array_filter([
            $this->detailAlamat,
            $this->desa?->namaDesa,
            $this->kecamatan?->namaKecamatan,
            $this->kabupaten?->namaKabupaten,
            $this->provinsi?->namaProvinsi,
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | 2FA HELPER
    |--------------------------------------------------------------------------
    */

    public function generateTwoFactorCode(): void
    {
        $this->update([
            'two_factor_code'       => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'two_factor_expires_at' => now()->addMinutes(10),
            'two_factor_verified'   => false,
        ]);
    }

    public function resetTwoFactorCode(): void
    {
        $this->update([
            'two_factor_code'       => null,
            'two_factor_expires_at' => null,
            'two_factor_verified'   => true,
        ]);
    }

    public function isTwoFactorCodeValid(): bool
    {
        return $this->two_factor_code !== null
            && $this->two_factor_expires_at?->isFuture();
    }

    public function isGoogleAccount(): bool
    {
        return $this->google_id !== null;
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI FITUR
    |--------------------------------------------------------------------------
    */

    public function chats()
    {
        return $this->hasMany(Chat::class, 'userId');
    }

    public function konsultasis()
    {
        return $this->hasMany(Konsultasi::class, 'userId');
    }

    public function kemitraans()
    {
        return $this->hasMany(Kemitraan::class, 'userId');
    }

    public function riwayatTransaksis()
    {
        return $this->hasMany(RiwayatTransaksi::class, 'userId');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'userId');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'userId');
    }
}