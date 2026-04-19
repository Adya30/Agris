<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing'); // nanti buat file landing.blade.php
})->name('landing');


/*
|--------------------------------------------------------------------------
| Auth Manual
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Google Login
|--------------------------------------------------------------------------
*/
Route::get('/auth/google', [SocialAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [SocialAuthController::class, 'callback']);


/*
|--------------------------------------------------------------------------
| Reset Password
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'resetForm'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');


/*
|--------------------------------------------------------------------------
| Dashboard (Setelah Login)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return "Selamat datang di Dashboard";
})->middleware('auth');