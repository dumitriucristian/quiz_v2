<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use \App\UserQuiz;
use \App\Result;

class ResultsController extends Controller
{
    public function index($userQuizId)
    {


        $result  = Result::where('user_quiz_id',$userQuizId)
                       ->get()
                       ->first();

        if($result == null){
            return 'ssss';
        }

        return view( 'pages.quizResult' , array("result"=>$result ) );
    }

    public function addResult($userQuizId)
    {


        if(Result::where('user_quiz_id',$userQuizId)->count() > 0){

            return redirect('result/'.$userQuizId);
        }

        (new UserQuiz)->quizIsComplete($userQuizId);
         $quizResult = \App\Result::setQuizResult( $userQuizId);
         $result = Result::create($quizResult);

        return redirect('result/'.$userQuizId);
    }




}
