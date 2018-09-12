<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {

        return  view('pages.guestLogin');
    }

    public function loginAsGuest(Request $request)
    {


        if($request->cookie('quest') == null){
            $anonimUser =  (new \App\User)->createAnonimUser();
            dd($anonimUser);
            return ;
        }
            //register new guest user
            //login as guest user
            //create guest cookie

    }

    private function isValidCookie($cookie){
        //check if cookie exist and is a valid cookie
    }
}
