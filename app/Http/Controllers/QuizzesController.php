<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Quiz;

class QuizzesController extends Controller
{
    public function homePage()
    {
            $quizzes = Quiz::all();

            if( !$quizzes || $quizzes->count() == 0) {

                return view('pages.home')->withErrors( ["errors"=>["Are no Quizzess in this moment"]]);
            }

            return view('pages.home', ["quizzes"=>$quizzes]);


    }

    public function quizDetails(Request $request)
    {

            $quiz = Quiz::find($request->id);

            if(!$quiz || empty($quiz)) {

                return view('pages.quizDetails')->withErrors( array("errors" => ["The quiz requested is unavailable"]));
            }

        return view('pages.quizDetails', array('quiz' => $quiz));
    }
}
