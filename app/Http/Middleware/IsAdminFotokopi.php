<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminFotokopi
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
        // Admin Fotokopi (type = 2) atau Super Admin (type = 1) boleh akses
        if (Auth::guard('admin')->check()) {
            $adminType = Auth::guard('admin')->user()->type;
            if ($adminType == \App\Models\Admin::TYPE_SUPER_ADMIN || $adminType == \App\Models\Admin::TYPE_ADMIN_FOTOCOPY) {
                return $next($request);
            }
        }
        
        // Redirect dengan pesan error
        return redirect()->route('login.admin')->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin Fotokopi atau Super Admin.');
    }
}
