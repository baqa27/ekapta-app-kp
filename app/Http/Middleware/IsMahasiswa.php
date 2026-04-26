<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsMahasiswa
{
    /**
     * Middleware untuk memverifikasi autentikasi mahasiswa
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('mahasiswa')->user()) {
            return $next($request);
        }
        return redirect()->route('login.mahasiswa');
    }
}
