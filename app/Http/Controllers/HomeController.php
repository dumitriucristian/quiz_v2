<?php

namespace App\Http\Controllers;

use App\Exptions;
use Illuminate\Http\Request;
use \App\Quiz;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function index()
    {


        $quizzes = Quiz::all();
        if( $this->quizzesExist($quizzes) === false){
            return view('pages.home')->withErrors('Are no quizzes for the moment. Please add one.');
        }

        return view('pages.home', ['quizzes' => $quizzes ]);
    }


    public function quizzesExist($quizzes)
    {
       return ( (!$quizzes) || ($quizzes->count() == 0) ) ? false : true;
    }
}
