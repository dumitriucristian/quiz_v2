<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuestionValidAnswerSet extends Model
{
    protected $fillable = ['question_id', 'valid_answer'];

    public function Question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeGetValidAnswerSetByQuestionId($query, $question_id)
    {

        $response =   DB::table($this->getTable())
            ->select('valid_answer')
            ->where('question_id', $question_id )
            ->get()
            ->first()
            ->valid_answer;

        return $response ;


    }
}
