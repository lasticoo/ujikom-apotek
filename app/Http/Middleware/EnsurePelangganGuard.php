<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memastikan request datang dari pelanggan yang sudah login
 * (guard "pelanggan"). Pakai di route group pelanggan.
 */
class EnsurePelangganGuard
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('pelanggan')->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
