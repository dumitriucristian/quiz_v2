<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionValidAnswerSet extends Model
{
    protected $fillable = ['question_id', 'valid_answer'];

    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
}
