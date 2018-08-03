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
            !Auth::user() &&
            ( URL::current() != 'http://quiz.test/login') &&
            ( URL::current() != 'http://quiz.test/register')

        ){
            return  redirect('/login');

        }
        return $next($request);
    }
}
