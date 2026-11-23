<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Data wajib diisi!',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Data wajib diisi!',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return $this->redirectByRole($user)->with('success', 'Login Berhasil');
        }

        return back()->with('error', 'Email atau password salah.')->withInput($request->only('email'));
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'namaLengkap' => 'required|max:300',
            'noTelp' => 'required|regex:/^[0-9]+$/|unique:users,noTelp',
            'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'required' => 'Data harus diisi!',
            'email.unique' => 'Email sudah terdaftar.',
            'noTelp.unique' => 'Nomor telepon sudah digunakan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $otp = rand(100000, 999999);

        session([
            'register_data' => [
                'namaLengkap' => $validated['namaLengkap'],
                'email' => $validated['email'],
                'noTelp' => $validated['noTelp'],
                'password' => Hash::make($validated['password']),
                'isAdmin' => false,
            ],
            'register_otp' => $otp,
            'register_otp_expires' => now()->addMinutes(10),
            'register_otp_attempts' => 0,
        ]);

        Mail::send('emails.otp', ['otp' => $otp, 'namaLengkap' => $validated['namaLengkap']], function ($message) use ($validated) {
            $message->to($validated['email'])->subject('Kode OTP Verifikasi AGRIS');
        });

        return redirect()->route('otp.form')->with('success', 'Kode OTP telah dikirim ke email');
    }

    public function showOtpForm()
    {
        if (! session('register_data')) {
            return redirect()->route('register');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);

        if (! session('register_data')) {
            return redirect()->route('register');
        }

        $attempts = (int) session('register_otp_attempts', 0);
        if ($attempts >= 5) {
            return back()->with('error', 'Terlalu banyak percobaan kode OTP yang salah. Silakan minta kirim ulang OTP baru.');
        }

        if (now()->gt(session('register_otp_expires'))) {
            return back()->with('error', 'Kode OTP sudah kadaluarsa');
        }

        if ($request->otp != session('register_otp')) {
            session(['register_otp_attempts' => $attempts + 1]);
            $remaining = 4 - $attempts;
            $msg = $remaining > 0
                ? "Kode OTP salah. Sisa percobaan: {$remaining} kali."
                : 'Kode OTP salah. Batas percobaan habis, silakan klik kirim ulang OTP.';

            return back()->with('error', $msg);
        }

        $user = User::create(session('register_data'));
        session()->forget(['register_data', 'register_otp', 'register_otp_expires', 'register_otp_attempts']);

        Auth::login($user);

        return $this->redirectByRole($user)->with('success', 'Akun berhasil dibuat.');
    }

    public function resendOtp(Request $request)
    {
        if (! session('register_data')) {
            return redirect()->route('register');
        }

        $otp = rand(100000, 999999);
        session([
            'register_otp' => $otp,
            'register_otp_expires' => now()->addMinutes(10),
            'register_otp_attempts' => 0,
        ]);

        $email = session('register_data')['email'];

        Mail::send('emails.otp', ['otp' => $otp, 'namaLengkap' => session('register_data')['namaLengkap']], function ($message) use ($email) {
            $message->to($email)->subject('Resend: Kode OTP Verifikasi AGRIS');
        });

        return back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    public function redirectToGoogle(Request $request)
    {
        $key = 'google-redirect:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);

            return redirect()->route('login')->with('error', "Terlalu banyak permintaan redirect. Silakan tunggu {$seconds} detik.");
        }
        RateLimiter::hit($key, 60);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $key = 'google-callback:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);

            return redirect()->route('login')->with('error', "Terlalu banyak percobaan login Google. Silakan coba lagi dalam {$seconds} detik.");
        }
        RateLimiter::hit($key, 60);

        if ($request->has('error')) {
            Log::warning('Google OAuth callback cancelled or returned error: '.$request->input('error'));

            return redirect()->route('login')->with('error', 'Login dengan Google dibatalkan atau ditolak.');
        }

        if (! $request->has('code')) {
            return redirect()->route('login')->with('error', 'Permintaan otorisasi Google tidak valid.');
        }

        try {
            try {
                $googleUser = Socialite::driver('google')->user();
            } catch (InvalidStateException $e) {
                if (config('app.env') === 'local') {
                    $googleUser = Socialite::driver('google')->stateless()->user();
                } else {
                    Log::warning('Google OAuth InvalidStateException blocked in non-local environment: '.$e->getMessage());

                    return redirect()->route('login')->with('error', 'Sesi login Google tidak valid atau telah kadaluarsa (Anti-CSRF). Silakan coba lagi.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Login Exception: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $errorMessage = config('app.debug')
                ? 'Gagal login Google: '.$e->getMessage()
                : 'Gagal login Google. Silakan coba lagi atau hubungi administrator.';

            return redirect()->route('login')->with('error', $errorMessage);
        }

        $email = $googleUser->getEmail();
        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->route('login')->with('error', 'Akun Google tidak memiliki alamat email yang valid.');
        }

        try {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'namaLengkap' => Str::limit(strip_tags($googleUser->getName() ?: 'Pengguna Google'), 100),
                    'password' => Hash::make(Str::random(32)),
                    'noTelp' => null,
                    'isAdmin' => false,
                ]
            );
        } catch (QueryException $e) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'namaLengkap' => Str::limit(strip_tags($googleUser->getName() ?: 'Pengguna Google'), 100),
                    'password' => Hash::make(Str::random(32)),
                    'noTelp' => '08'.str_pad((string) random_int(10000000, 99999999), 10, '0', STR_PAD_LEFT),
                    'isAdmin' => false,
                ]
            );
        }

        Auth::login($user, true);

        return $this->redirectByRole($user)->with('success', 'Login Berhasil');
    }

    private function redirectByRole($user)
    {
        if ($user->isAdmin) {
            return redirect()->intended(route('admin.produk.index'));
        }

        return redirect()->intended(route('agen.produk.index'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset telah dikirim.')
            : back()->withErrors(['email' => 'Email tidak ditemukan.']);
    }

    public function resetForm($token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => request()->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset.')
            : back()->withErrors(['email' => __($status)]);
    }

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectByRole($request->user());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi baru telah dikirim.');
    }
}
