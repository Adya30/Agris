<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    // SHOW LOGIN
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ],
        [
            'email.required' => 'Data wajib diisi!',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Data wajib diisi!',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah.') -> withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user)->with('success', 'Login Berhasil' . $user->name);
    }

    // RESET
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ],[
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
        ]
        );

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('login')
                ->with('success', 'Link reset telah dikirim ke email Anda.');
        }

        return back()->withErrors([
            'email' => 'Email tidak ditemukan atau belum terdaftar.'
        ]);
    }
    public function resetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->email
        ]);
    }

    // PROSES RESET
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols() ],
        ],
        [
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',
        ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login') ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

    // LOGIN GOOGLE
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
                'password' => Hash::make(Str::random(12)),
                'noTelp' => '-',
                'tanggalLahir' => null,
                'isAdmin' => false,
            ]
        );

        Auth::login($user, true);

        return $this->redirectByRole($user)->with('success', 'Login Berhasil' . $user->name);
    }

    // REGISTER OTP
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    public function showOtpForm()
    {
        if (!session('register_data')) {
            return redirect()->route('register');
        }

        return view('auth.otp');
    }

    public function register(Request $request)
    {
        $validated = $request->validate(
        [
            'username' => 'required|string|min:4|max:30|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'namaLengkap' => 'required|max:300',
            'noTelp' => 'required|regex:/^[0-9]+$/|unique:users,noTelp',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols() ],
        ],
        [
            'username.required' => 'Username wajib diisi.',
            'username.min' => 'Username minimal 4 karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'namaLengkap.required' => 'Nama lengkap wajib diisi.',
            'namaLengkap.max' => 'Nama Lengkap maksimal 300 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            'noTelp.required' => 'Nomor telepon wajib diisi.',
            'noTelp.regex' => 'Nomor telepon hanya boleh angka.',
            'noTelp.unique' => 'Nomor telepon sudah digunakan.',

            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',
        ]
    );

        $otp = rand(100000, 999999);

        session([
            'register_data' => [
                'username' => $validated['username'],
                'namaLengkap' => $validated['namaLengkap'],
                'email' => $validated['email'],
                'noTelp' => $validated['noTelp'],
                'password' => Hash::make($validated['password']),
                'isAdmin' => false
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

        return redirect()->route('agen.profile') ->with('success', 'Akun berhasil dibuat.');
    }


    // ROLE
    private function redirectByRole($user)
    {
        if ($user->isAdmin) {
            return redirect()->route('admin.produk.index');
        }

        return redirect()->route('agen.profile');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
