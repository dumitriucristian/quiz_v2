<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['quiz_id', 'body'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answer()
    {
        return $this->hasMany( Answer::class);
    }

    public function valid_answer_set(){

        return $this->hasOne(QuestionValidAnswerSet::class);
    }

}
