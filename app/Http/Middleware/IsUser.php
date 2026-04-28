<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && !Auth::user()->isAdmin) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->isAdmin) {
            return redirect()->route('admin.produk.index')->with('error', 'Admin tidak boleh akses area Agen');
        }

        return redirect()->route('login');
    }
}
