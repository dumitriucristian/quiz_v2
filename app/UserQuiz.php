<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserQuiz extends Model
{


    public function init($data)
    {
        $this->user_id = $data['user_id'];
        $this->quiz_id = $data['quiz_id'];

        $this->save();

        return $this->id;
    }

    public function findUserQuiz($data){


      return   DB::table($this->table)
            ->where($data['quiz_id'] , '=','quiz_id')
            ->where($data['user_id'],'=', 'user_id')
            ->where('completed_at', '!=' ,'NULL')
            ->count('*');

    }

}
