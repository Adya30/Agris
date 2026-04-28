<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin) {
            return $next($request);
        }

        if (Auth::check() && !Auth::user()->isAdmin) {
            return redirect()->route('agen.profile')->with('error', 'Akses ditolak');
        }

        return redirect()->route('login');
    }
}
