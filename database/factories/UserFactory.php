<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Desa;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => Str::ulid(), // Penting karena primary key ULID
            'username' => $this->faker->unique()->userName(),
            'password' => Hash::make('password'), // Default password: password
            'namaLengkap' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'noTelp' => $this->faker->phoneNumber(),
            'detailAlamat' => $this->faker->address(),
            'isAdmin' => false,
            'isActive' => false, // Default false untuk testing logika
            'desaId' => Desa::factory(), // Otomatis buat desa baru
            'email_verified_at' => now(),
        ];
    }
}