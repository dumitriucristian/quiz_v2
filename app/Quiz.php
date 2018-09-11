<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quiz extends Model
{

    protected $fillable = ['title', 'description'];


    public function questions()
    {
        return $this->belongsToMany('App\Question', 'question_quizzes' );
    }

    public static function quizExist($data)
    {
        return ( (!$data) || ($data->count() == 0) ) ? false : true;
    }

    public function getNrOfQuestionsAttribute()
    {
        return $this->questions->count();
    }

    public function quizProgress($nrOfQuestions, $nrOfAnswers)
    {

        return  ($nrOfAnswers == 0) ?  0 : (int) $nrOfAnswers * 100 / $nrOfQuestions ;
    }




}
