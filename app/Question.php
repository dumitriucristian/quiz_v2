<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Question extends Model
{
    protected $fillable = ['body','category_id'];

    public function quiz()
    {
        return $this->belongsToMany('App\Quiz', 'question_quizzes', 'question_id', 'quiz_id');
    }


    public function tags()
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }


    public function answer()
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


    public function addCategory($categoryId, $questionId)
    {

        $question = DB::table( $this->getTable())
            ->where('id','=', $questionId)
            ->update(['category_id'=> $categoryId]);

        return $question;

    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

 }
