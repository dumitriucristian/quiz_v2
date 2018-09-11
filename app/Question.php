<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    protected $fillable = ['body'];

    public function quiz()
    {
        return $this->belongsToMany('App\Question', 'question_quizzes', 'question_id', 'quiz_id');
    }

    public function Answer()
    {
        return $this->hasMany( 'App\Answer');
    }

    public function valid_answer_set(){

        return $this->hasOne(QuestionValidAnswerSet::class);
    }

    public function nextQuestionId($lastQuestionAnsweredId, $quiz_id)
    {

      $sql = DB::table( $this->getTable() )
                ->select('id')
                ->where('id', '>', $lastQuestionAnsweredId)
                ->get();

      if($sql->count() > 0) {

          return $sql->first()->id;
      }

      return false;

    }
 }
