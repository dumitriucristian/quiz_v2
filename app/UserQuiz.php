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
                ->where('completed_at', '=' ,NULL)
                ->count();

    }

    public function quizIsIncomplete($user_id, $quiz_id)
    {
            $nrOfIncompleteQuizzes = DB::table($this->getTable())
                ->where('quiz_id' , '=', $quiz_id)
                ->where('user_id','=', $user_id)
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






}
