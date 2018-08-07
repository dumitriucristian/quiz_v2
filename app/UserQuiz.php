<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserQuiz extends Model
{
    protected $fillable = ['quiz_id', 'user_id','completed_at'];

    public function init($data)
    {
        $this->user_id = $data['user_id'];
        $this->quiz_id = $data['quiz_id'];

        $this->save();

        return $this->id;
    }

    public function findUserQuiz($data){


        return  DB::table($this->getTable())
                ->where('quiz_id' , '=',$data['quiz_id'])
                ->where('user_id','=', $data['user_id'])
                ->where('completed_at', '=' ,NULL)
                ->count();

    }

}
