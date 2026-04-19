<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Kemitraan;
use App\Observers\KemitraanObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Kemitraan::observe(KemitraanObserver::class);
    }
}