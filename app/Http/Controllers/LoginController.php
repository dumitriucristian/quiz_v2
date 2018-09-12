<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {

        return  view('pages.guestLogin');
    }

    public function loginAsGuest()
    {
            //register new guest user
            //login as guest user
            //create guest cookie

    }
}
