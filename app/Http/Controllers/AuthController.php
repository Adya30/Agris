<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER PAGE
    |--------------------------------------------------------------------------
    */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('username', $request->username)
                    ->orWhere('email', $request->username)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Username/email atau password salah.')
                         ->withInput($request->only('username'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    /*
    |--------------------------------------------------------------------------
    | GOOGLE LOGIN
    |--------------------------------------------------------------------------
    */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'namaLengkap' => $googleUser->getName(),
                'username' => str_replace(' ', '', strtolower($googleUser->getName())) . rand(100,999),
                'password' => Hash::make(rand(100000,999999)),
                'noTelp' => '0000000000',
                'is_admin' => false
            ]
        );

        Auth::login($user, true);

        return $this->redirectByRole($user);
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER + OTP
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:4|unique:users,username',
            'namaLengkap' => 'required|max:300',
            'email' => 'required|email|unique:users,email',
            'noTelp' => 'required|regex:/^[0-9]+$/|unique:users,noTelp',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols()
            ],
        ]);

        $otp = rand(100000, 999999);

        session([
            'register_data' => [
                'namaLengkap' => $validated['namaLengkap'],
                'username' => $validated['username'],
                'email' => $validated['email'],
                'noTelp' => $validated['noTelp'],
                'password' => Hash::make($validated['password']),
                'is_admin' => false
            ],
            'register_otp' => $otp,
            'register_otp_expires' => now()->addMinutes(10)
        ]);

        Mail::raw("Kode OTP AGRIS kamu adalah: $otp\nBerlaku 10 menit.",
            function ($message) use ($validated) {
                $message->to($validated['email'])
                        ->subject('Kode OTP Verifikasi AGRIS');
            }
        );

        return redirect()->route('otp.form')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if (!session('register_data')) {
            return redirect()->route('register');
        }

        if (now()->gt(session('register_otp_expires'))) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa');
        }

        if ($request->otp != session('register_otp')) {
            return back()->with('error', 'Kode OTP salah');
        }

        $user = User::create(session('register_data'));

        session()->forget([
            'register_data',
            'register_otp',
            'register_otp_expires'
        ]);

        Auth::login($user);

        return redirect()->route('profile')
            ->with('success', 'Akun berhasil dibuat.');
    }

    /*
    |--------------------------------------------------------------------------
    | REDIRECT BY ROLE
    |--------------------------------------------------------------------------
    */
    private function redirectByRole($user)
    {
        if ($user->isAdmin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('profile');
    }
}