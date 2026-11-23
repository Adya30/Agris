<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

test('multiple google users can be created with null noTelp without unique violation', function () {
    $user1 = User::create([
        'email' => 'user1@gmail.com',
        'namaLengkap' => 'Google User 1',
        'password' => Hash::make(Str::random(32)),
        'noTelp' => null,
        'isAdmin' => false,
    ]);

    $user2 = User::create([
        'email' => 'user2@gmail.com',
        'namaLengkap' => 'Google User 2',
        'password' => Hash::make(Str::random(32)),
        'noTelp' => null,
        'isAdmin' => false,
    ]);

    expect($user1->exists)->toBeTrue();
    expect($user2->exists)->toBeTrue();
    expect($user1->noTelp)->toBeNull();
    expect($user2->noTelp)->toBeNull();
});
