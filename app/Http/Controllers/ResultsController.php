<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\UserQuiz;

class ResultsController extends Controller
{
    public function index()
    {

    }

    public function addResult($userQuizId)
    {
        (new UserQuiz)->quizIsComplete($userQuizId);

        return "add results page";
    }
}
