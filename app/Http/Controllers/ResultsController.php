<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use \App\UserQuiz;

class ResultsController extends Controller
{
    public function index()
    {

    }

    public function addResult($userQuizId)
    {
        (new UserQuiz)->quizIsComplete($userQuizId);
        $resultsData = array(
            "user_quiz_id" => $userQuizId
        );

        dd($resultsData);
    }

    public  function setQuizResult($user_quiz_id)
    {

    }
}
