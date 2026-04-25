<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->isAdmin) {
            abort(403, 'Akses ditolak. Halaman khusus user.');
        }

        return $next($request);
    }
}
