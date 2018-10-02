<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cookie;
use App\User;

class LoginController extends Controller
{

    public function login(Request $request)
    {


       if (
           ($request->cookie('guest') != null)
           && (new \App\User)->isValidGuest($request->cookie('guest'))

       ){
           Auth::loginUsingId( (new \App\User)->getUserIdByCookie($request->cookie('guest')));
           return redirect('/') ;
       }

      return  view('pages.guestLogin');
    }

    public function loginAsGuest(Request $request)
    {


        if($request->cookie('guest') == null){
            $anonimUser =  (new \App\User)->createAnonimUser();
            Cookie::queue('guest', $anonimUser->email, 5);
            Auth::loginUsingID($anonimUser->id,true);
            Auth::user()->id;
            return redirect('/') ;
        }

        return redirect('/') ;

    }

    public function logout(Request $request){

        Auth::logout();
        if($request->cookie('guest') != null) {
           $cookie = Cookie::forget('guest');

            return redirect('/login')->withCookie($cookie);
        }

        return redirect('/login');
    }




}
