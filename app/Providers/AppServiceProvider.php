<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Kemitraan;
use App\Models\User;
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

        View::composer('*', function ($view) {
            $admin = User::where('isAdmin', true)->first();
            $view->with('admin', $admin);
        });
    }
}
