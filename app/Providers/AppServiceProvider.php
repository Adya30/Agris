<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Kemitraan;
use App\Models\User;
use App\Observers\KemitraanObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Load custom application helpers
        if (file_exists(app_path('Helpers/helpers.php'))) {
            require_once app_path('Helpers/helpers.php');
        }

        // Production-safe fallback in case helpers.php is not yet deployed or autoload is not updated
        if (! function_exists('storage_url')) {
            function storage_url(?string $path, ?string $disk = null): ?string
            {
                if (blank($path)) {
                    return null;
                }

                if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//') || str_starts_with($path, 'data:')) {
                    return $path;
                }

                $cleanPath = ltrim($path, '/');
                if (str_starts_with($cleanPath, 'storage/')) {
                    $cleanPath = substr($cleanPath, 8);
                }

                $targetDisk = $disk ?: config('filesystems.default', 'public');

                if ($targetDisk === 'public') {
                    return asset('storage/'.$cleanPath);
                }

                try {
                    return Storage::disk($targetDisk)->url($cleanPath);
                } catch (\Throwable $e) {
                    return asset('storage/'.$cleanPath);
                }
            }
        }
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
