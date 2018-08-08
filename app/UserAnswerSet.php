<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswerSet extends Model
{
    public function scopeSetUserAnswer($query, $data)
    {
        return $answer = implode('',$data['answers']);

    }
}
