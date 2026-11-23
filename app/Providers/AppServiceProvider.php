<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Kemitraan;
use App\Models\User;
use App\Observers\KemitraanObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
                $admin = User::isAdmin(true)->first();
            }

            $unreadCount = 0;
            if (Auth::check()) {
                /** @var Builder<Chat> $q */
                $q = Chat::query()
                    ->where('id_penerima', Auth::id())
                    ->where('status', 'terkirim');
                $unreadCount = $q->count();
            }

            $view->with([
                'admin' => $admin,
                'unread_messages_count' => $unreadCount,
            ]);
        });
    }
}
