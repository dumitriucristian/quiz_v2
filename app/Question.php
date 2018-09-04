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

      $sql = DB::table( $this->getTable() )
                ->select('id')
                ->where('quiz_id', '=' , $quiz_id)
                ->where('id', '>', $lastQuestionAnsweredId)
                ->get();
      if($sql->count() > 0) {

          return $sql->first()->id;
      }

      return false;

    }

    public function nrOfQuestionByQuizId($quiz_Id)
    {
        return DB::table($this->getTable())
            ->where('quiz_id', '=', $quiz_Id)
            ->count();
    }


    public function isLastQuestion($quiz_id, $question_id)
    {
        $nrOfQuestions =  DB::table($this->getTable())
                ->where('quiz_id', '=', $quiz_id)
                ->where('id', '>' , $question_id)
                ->count();



        return ($nrOfQuestions == 0) ? true  : false ;
    }



}
