<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserQuiz extends Model
{
    protected $fillable = ['quiz_id', 'user_id','completed_at'];



    public function scopeInit($query, $data)
    {
        $this->user_id = $data['user_id'];
        $this->quiz_id = $data['quiz_id'];

        $this->save();

        return $this->id;
    }

    public function scopeFindUserQuiz($query, $data){


        return  $query->where('quiz_id' , '=',$data['quiz_id'])
                ->where('user_id','=', $data['user_id'])
                ->where('completed_at', '=', NULL)
                ->get()
                ->last()->id;

    }



    public function scopeCheckUserQuizExist($query, $data){


        return  $query->where('quiz_id' , '=',$data['quiz_id'])
            ->where('user_id','=', $data['user_id'])
            ->where('completed_at', '=', NULL)
            ->count();

    }

    public function quizIsIncomplete($user_quiz_id)
    {
            $nrOfIncompleteQuizzes = DB::table($this->getTable())
                ->where('id','=', $user_quiz_id)
                ->where('completed_at', '=' ,NULL)
                ->count();

            return ($nrOfIncompleteQuizzes > 0) ? true : false ;
    }


    public function getIncompleteUserQuizId($user_id, $quiz_id)
    {
       return DB::table($this->getTable())
            ->select('id')
            ->where('quiz_id' , '=', $quiz_id)
            ->where('user_id','=', $user_id)
            ->where('completed_at', '=' ,NULL)
            ->get()->last()->id;
    }

    public function quizIsComplete($user_quiz_id)
    {
       $userQuiz = UserQuiz::find($user_quiz_id);
       $userQuiz->completed_at = now();
       $userQuiz->save();

    }

}
