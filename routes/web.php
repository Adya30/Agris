<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\c_profile;
use App\Http\Controllers\c_wilayah;
use App\Http\Controllers\c_produk;
use App\Http\Controllers\c_blog;
use App\Http\Controllers\c_kemitraan;

Route::get('/', function () {
    return view('guest.landing');
})->name('landing');

Route::get('/about', function () {
    return view('guest.about');
})->name('about');

Route::get('/contact', function () {
    return view('guest.contact');
})->name('contact');

Route::get('/blog', [c_blog::class, 'indexGuest'])->name('guest.blog.index');
Route::get('/blog/{id}', [c_blog::class, 'showGuest'])->name('guest.blog.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/email/verification-notification', [AuthController::class, 'sendVerificationEmail'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::prefix('agen')->middleware('isUser')->group(function () {
        Route::get('/profile', [c_profile::class, 'show'])->name('agen.profile');
        Route::put('/profile', [c_profile::class, 'update'])->name('agen.profile.update');

        Route::get('/produk', [c_produk::class, 'indexAgen'])->name('agen.produk.index');
        Route::get('/produk/{id}', [c_produk::class, 'showAgen'])->name('agen.produk.show');

        Route::get('/blog', [c_blog::class, 'indexAgen'])->name('agen.blog.index');
        Route::get('/blog/{id}', [c_blog::class, 'showAgen'])->name('agen.blog.show');

        Route::get('/kemitraan', [c_kemitraan::class, 'index'])->name('kemitraan.index');
        Route::get('/kemitraan/ajukan', [c_kemitraan::class, 'create'])->name('kemitraan.create');
        Route::post('/kemitraan', [c_kemitraan::class, 'store'])->name('kemitraan.store');
        Route::post('/kemitraan/upload-mou/{id}', [c_kemitraan::class, 'uploadMou'])->name('kemitraan.uploadMou');
    });

    Route::prefix('admin')->middleware('isAdmin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.produk.index');
        });

        Route::get('/profile', [c_profile::class, 'show'])->name('profile');
        Route::put('/profile', [c_profile::class, 'update'])->name('profile.update');

        Route::get('produk/trash', [c_produk::class, 'trash'])->name('produk.trash');
        Route::get('produk/{id}/restore', [c_produk::class, 'restore'])->name('produk.restore');
        Route::delete('produk/{id}/force-delete', [c_produk::class, 'forceDelete'])->name('produk.forceDelete');
        Route::post('kategori', [c_produk::class, 'storeKategori'])->name('kategori.store');

        Route::resource('produk', c_produk::class);
        Route::resource('blog', c_blog::class);

        Route::get('/kemitraan', [c_kemitraan::class, 'index'])->name('kemitraan.index');
        Route::get('/kemitraan/{id}', [c_kemitraan::class, 'show'])->name('kemitraan.show');
        Route::post('/kemitraan/action/{id}', [c_kemitraan::class, 'adminAction'])->name('kemitraan.action');
        Route::post('/kemitraan/verify-mou/{id}', [c_kemitraan::class, 'verifyMou'])->name('kemitraan.verifyMou');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('wilayah')->group(function () {
    Route::get('/provinsi', [c_wilayah::class, 'getProvinsi'])->name('wilayah.provinsi');
    Route::get('/kabupaten/{id}', [c_wilayah::class, 'getKabupaten'])->name('wilayah.kabupaten');
    Route::get('/kecamatan/{id}', [c_wilayah::class, 'getKecamatan'])->name('wilayah.kecamatan');
    Route::get('/desa/{id}', [c_wilayah::class, 'getDesa'])->name('wilayah.desa');
});
