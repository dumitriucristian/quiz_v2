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
            && ( $request->route()->uri != 'login')
            && ( $request->route()->uri != 'register')
        ){

            return redirect('/login');

        }
        return $next($request);
    }
}
