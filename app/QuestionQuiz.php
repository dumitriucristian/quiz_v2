<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionQuiz extends Model
{

   protected $fillable = ['question_id', 'quiz_id'];

   public function questions()
   {
       return $this->belongsToMany('App\Question', 'question_quizzes', 'question_id', 'quiz_id');
   }

    public function quiz()
    {
        return $this->belongsToMany('App\Question', 'question_quizzes', 'question_id', 'quiz_id');
    }
}
