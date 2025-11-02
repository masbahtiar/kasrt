<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
        //    return redirect('/home');
        //}
            if ( Auth::user()->roles()->first()->role_name == 'admin' ) {// do your margic here
                return redirect()->route('admin');
            }
            if ( Auth::user()->roles()->first()->role_name == 'sekolah' ) {// do your margic here
                return redirect()->route('sekolah');
            }
            if ( Auth::user()->roles()->first()->role_name == 'verifikasi' ) {// do your margic here
                return redirect()->route('verifikasi');
            }
        }
        return $next($request);
    }
}
