<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{

    public function UserQuiz()
    {
        return $this->belongsToMany('\App\UserQuiz');
    }

    public function Question()
    {
        return $this->belongsTo('\App\Question');
    }
}
