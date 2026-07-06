<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Hanya Super Admin (type = 1) yang boleh akses
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->type == \App\Models\Admin::TYPE_SUPER_ADMIN) {
            return $next($request);
        }
        
        // Jika sudah login tapi bukan Super Admin
        if (Auth::guard('admin')->check()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Super Admin.');
        }
        
        // Jika belum login
        return redirect()->route('login.admin')->with('error', 'Silakan login terlebih dahulu.');
    }
}
