<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $level)
    {
        if (auth()->user() && auth()->user()->level == $level) {
            return $next($request);
        }

        return redirect()->route('dashboard'); // Ganti dengan rute yang sesuai untuk pengguna yang tidak memiliki hak akses.
    }

}
