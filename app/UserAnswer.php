<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $quard =['id'];

    /*public function UserQuiz()
    {
        return $this->belongsToMany('\App\UserQuiz');
    }

    public function Question()
    {
        return $this->belongsTo('\App\Question');
    }
    */

    public function scopeSaveEachUserAnswer($query, $userData)
    {


        $answers  = $userData['answer'];

        foreach($answers as $key => $value ){

            $userAnswer = new UserAnswer;
            $userAnswer->user_quiz_id = $userData['user_quiz_id'];
            $userAnswer->question_id = $userData['question_id'];
            $userAnswer->answer_id = $key;
            $userAnswer->user_answer = $value;
            $userAnswer->is_valid = $this->scopeIsValid( $query, $key, $value);
           // dd($userAnswer->scopeIsValid( $query, $key, $value));
            $userAnswer->save();
        }

    }

    public function scopeUpdateEachUserAnswer($query, $userData)
    {

        $answers  = $userData['answer'];

        foreach($answers as $key => $value ){

            DB::table($this->getTable())
            ->where('user_quiz_id', $userData['user_quiz_id'] )
            ->where('question_id', $userData['question_id'])
            ->where('answer_id', $key)
            ->update( array(
                 'user_answer' => $value,
                'is_valid'    => $this->scopeIsValid( $query, $key, $value)
                )
            );
        }
    }

    public function scopeIsValid($query, $answerId, $answerValue)
    {


          $valid = \App\Answer::where('id', $answerId)
              ->where('correct','=',$answerValue)
              ->count();

         return $valid == 0 ? false : true ;
    }

}
