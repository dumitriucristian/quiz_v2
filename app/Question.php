<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function nextQuestionId($lastQuestionAnsweredId, $quiz_id)
    {
       return DB::table( $this->getTable() )
                ->select('id')
                ->where('quiz_id', '=' , $quiz_id)
                ->where('id', '>', $lastQuestionAnsweredId)
                ->get()
                ->first()->id;
    }




}
