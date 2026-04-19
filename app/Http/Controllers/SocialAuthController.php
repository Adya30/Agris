<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->email],
            [
                'username' => $googleUser->name,
                'namaLengkap' => $googleUser->name,
                'password' => bcrypt(Str::random(16)),
                'noTelp' => '-'
            ]
        );

        Auth::login($user);

        return redirect('/dashboard');
    }
}