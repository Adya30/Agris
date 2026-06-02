<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Kemitraan;
use App\Models\User;
use App\Models\Chat;
use App\Observers\KemitraanObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Kemitraan::observe(KemitraanObserver::class);

        View::composer('*', function ($view) {
            static $admin = null;

            if (is_null($admin)) {
                $admin = User::where('isAdmin', true)->first();
            }

            $unreadCount = 0;
            if (Auth::check()) {
                $unreadCount = Chat::where('penerima_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
            }

            $view->with([
                'admin' => $admin,
                'unread_messages_count' => $unreadCount
            ]);
        });
    }
}
