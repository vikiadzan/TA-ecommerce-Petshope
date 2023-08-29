<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MemberAuthMiddleware
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
        // Cek apakah pengguna sudah login atau belum
        if (!auth()->guard('webmember')->check()) {
            // Jika belum login, arahkan ke halaman login_member
            return route('login_member');
        }

        // Jika sudah login, lanjutkan permintaan ke route berikutnya
        return $next($request);
    }

}
