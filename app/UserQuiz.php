<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    public function init($data)
    {
        $this->user_id = $data['user_id'];
        $this->quiz_id = $data['quiz_id'];

        $this->save();

        return $this->id;
    }
}
