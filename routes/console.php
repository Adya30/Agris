<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Http\Controllers\c_pesanan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    c_pesanan::cancelOldOrders();
})->daily();
