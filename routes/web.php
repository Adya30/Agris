<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\c_blog;
use App\Http\Controllers\c_chat;
use App\Http\Controllers\c_kemitraan;
use App\Http\Controllers\c_keranjang;
use App\Http\Controllers\c_laporan;
use App\Http\Controllers\c_pembayaran;
use App\Http\Controllers\c_pesanan;
use App\Http\Controllers\c_produk;
use App\Http\Controllers\c_profile;
use App\Http\Controllers\c_wilayah;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

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
    Route::post('/validate-register', [AuthController::class, 'validateRegister'])->name('validate.register');

    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:5,1')->name('otp.verify');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:3,1')->name('otp.resend');

    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->middleware('throttle:10,1')->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->middleware('throttle:10,1')->name('google.callback');

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

    Route::get('/chat/{id}', [c_chat::class, 'show'])->middleware('throttle:30,1')->name('chat.show');
    Route::post('/chat', [c_chat::class, 'store'])->middleware('throttle:15,1')->name('chat.store');
    Route::delete('/chat/{id}', [c_chat::class, 'destroy'])->middleware('throttle:15,1')->name('chat.destroy');

    Route::prefix('agen')->middleware('isUser')->group(function () {
        Route::get('/profile', [c_profile::class, 'show'])->name('agen.profile');
        Route::put('/profile', [c_profile::class, 'update'])->middleware('throttle:10,1')->name('agen.profile.update');

        Route::get('/produk', [c_produk::class, 'indexAgen'])->name('agen.produk.index');
        Route::get('/produk/{id}', [c_produk::class, 'showAgen'])->name('agen.produk.show');

        Route::get('/keranjang', [c_keranjang::class, 'index'])->name('agen.keranjang.index');
        Route::post('/produk/add-to-cart', [c_keranjang::class, 'tambah'])->name('agen.produk.add-to-cart');
        Route::post('/keranjang/tambah/{id}', [c_keranjang::class, 'tambahJumlah'])->name('agen.keranjang.tambah');
        Route::post('/keranjang/kurang/{id}', [c_keranjang::class, 'kurang'])->name('agen.keranjang.kurang');
        Route::post('/keranjang/update/{id}', [c_keranjang::class, 'updateJumlah'])->name('agen.keranjang.update');
        Route::delete('/keranjang/{id}', [c_keranjang::class, 'destroy'])->name('agen.keranjang.destroy');

        Route::get('/blog', [c_blog::class, 'indexAgen'])->name('agen.blog.index');
        Route::get('/blog/{id}', [c_blog::class, 'showAgen'])->name('agen.blog.show');

        Route::get('/kemitraan', [c_kemitraan::class, 'index'])->name('kemitraan.index');
        Route::get('/kemitraan/ajukan', [c_kemitraan::class, 'create'])->name('kemitraan.create');
        Route::post('/kemitraan', [c_kemitraan::class, 'store'])->middleware('throttle:6,1')->name('kemitraan.store');
        Route::post('/kemitraan/upload-mou/{id}', [c_kemitraan::class, 'uploadMou'])->middleware('throttle:6,1')->name('kemitraan.uploadMou');

        Route::get('/chat', [c_chat::class, 'index'])->name('agen.chat.index');
        Route::post('/chatbot/record-usage', [c_chat::class, 'recordChatbotUsage'])->middleware('throttle:15,1')->name('agen.chatbot.record');

        Route::get('/checkout', [c_pesanan::class, 'checkoutForm'])->name('agen.checkout.form');
        Route::post('/checkout', [c_pesanan::class, 'checkoutStore'])->name('agen.checkout.store');
        Route::post('/checkout/cek-ongkir', [c_pesanan::class, 'cekOngkir'])->middleware('throttle:10,1')->name('agen.checkout.cek-ongkir');
        Route::get('/pesanan', [c_pesanan::class, 'index'])->name('agen.pesanan.index');
        Route::get('/pesanan/{id}', [c_pesanan::class, 'show'])->name('agen.pesanan.show');
        Route::post('/pesanan/{id}/batal', [c_pesanan::class, 'cancelOrder'])->name('agen.pesanan.batal');
        Route::post('/pesanan/{id}/bayar-simulasi', [c_pembayaran::class, 'bayarSimulasi'])->name('agen.pesanan.bayar-simulasi');
        Route::post('/pesanan/{id}/batal-checkout', [c_pembayaran::class, 'batalCheckout'])->name('agen.pesanan.batal-checkout');
        Route::post('/pesanan/{id}/cek-status', [c_pembayaran::class, 'cekStatus'])->name('agen.pesanan.cek-status');
        Route::post('/pesanan/{id}/diterima', [c_pesanan::class, 'markDiterima'])->name('agen.pesanan.diterima');
        Route::get('/pesanan/{id}/lacak', [c_pesanan::class, 'lacakPengiriman'])->name('agen.pesanan.lacak');

    });

    Route::prefix('admin')->middleware('isAdmin')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.produk.index');
        });

        Route::get('/profile', [c_profile::class, 'show'])->name('profile');
        Route::put('/profile', [c_profile::class, 'update'])->middleware('throttle:15,1')->name('profile.update');

        Route::get('produk/trash', [c_produk::class, 'trash'])->name('produk.trash');
        Route::get('produk/{id}/restore', [c_produk::class, 'restore'])->name('produk.restore');
        Route::delete('produk/{id}/force-delete', [c_produk::class, 'forceDelete'])->name('produk.forceDelete');
        Route::post('kategori', [c_produk::class, 'storeKategori'])->name('kategori.store');

        Route::resource('produk', c_produk::class);
        Route::resource('blog', c_blog::class);

        Route::get('/kemitraan', [c_kemitraan::class, 'index'])->name('kemitraan.index');
        Route::get('/kemitraan/{id}', [c_kemitraan::class, 'show'])->name('kemitraan.show');
        Route::get('/kemitraan/{id}/preview-mou', [c_kemitraan::class, 'previewMou'])->name('kemitraan.previewMou');
        Route::post('/kemitraan/action/{id}', [c_kemitraan::class, 'adminAction'])->name('kemitraan.action');
        Route::post('/kemitraan/verify-mou/{id}', [c_kemitraan::class, 'verifyMou'])->name('kemitraan.verifyMou');

        Route::get('/chat', [c_chat::class, 'index'])->name('chat.index');

        Route::get('/pesanan', [c_pesanan::class, 'adminIndex'])->name('pesanan.index');
        Route::get('/pesanan/{id}', [c_pesanan::class, 'adminShow'])->name('pesanan.show');
        Route::post('/pesanan/{id}/action', [c_pesanan::class, 'adminAction'])->name('pesanan.action');
        Route::get('/laporan', [c_laporan::class, 'index'])->name('laporan.index');

    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('wilayah')->middleware('throttle:60,1')->group(function () {
    Route::get('/provinsi', [c_wilayah::class, 'getProvinsi'])->name('wilayah.provinsi');
    Route::get('/kabupaten/{id}', [c_wilayah::class, 'getKabupaten'])->name('wilayah.kabupaten');
    Route::get('/kecamatan/{id}', [c_wilayah::class, 'getKecamatan'])->name('wilayah.kecamatan');
    Route::get('/desa/{id}', [c_wilayah::class, 'getDesa'])->name('wilayah.desa');
});

Route::post('/midtrans/callback', [c_pembayaran::class, 'paymentCallback'])->middleware('throttle:60,1')->name('midtrans.callback');
Route::post('/biteship/webhook', [c_pesanan::class, 'biteshipWebhook'])->middleware('throttle:60,1')->name('biteship.webhook');

// Fallback route untuk storage di cPanel jika symlink public/storage belum dibuat / tidak didukung hosting
Route::get('/storage/{path}', function (string $path) {
    if (str_contains($path, '..') || str_contains($path, "\0")) {
        abort(403);
    }

    $cleanPath = ltrim($path, '/');
    $disk = config('filesystems.default', 'public');

    if (! Storage::disk($disk)->exists($cleanPath)) {
        abort(404);
    }

    $mime = Storage::disk($disk)->mimeType($cleanPath) ?: 'application/octet-stream';

    return Storage::disk($disk)->response($cleanPath, null, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->where('path', '.*')->name('storage.fallback');
