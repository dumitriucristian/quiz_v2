<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;


class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(
            ( Auth::check() == false )
            && ( $request->path() != 'login')
            && ( $request->path() != 'register')
            && ( $request->path() != 'guest-login')
            && ( $request->path() != 'loginAsGuest')
        ){

            return redirect('guest-login');
        }

         return $next($request);
    }
}
