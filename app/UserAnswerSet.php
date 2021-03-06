<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserAnswerSet extends Model
{

    protected $guarded = array('id');

    public function scopeSetUserAnswer($query, $answers)
    {
        return $answer = implode('',$answers);

    }

    public function scopeSaveUserAnswerSet($query, $data)
    {

        $userAnswerSet = new UserAnswerSet();
        $userAnswerSet->question_id = $data["question_id"];
        $userAnswerSet->user_answer_set = $data["user_answer_set"];
        $userAnswerSet->user_quiz_id = $data["user_quiz_id"];
        $userAnswerSet->is_valid = $this->isValid($data['question_id'], $data['user_answer_set']);

        $userAnswerSet->save();

    }

    public function isValid($question_id, $answer)
    {

        //get valid answer
        return ( QuestionValidAnswerSet::getValidAnswerSetByQuestionId($question_id)  == $answer) ? TRUE : FALSE ;


    }

    public function scopeUserAnswerSetExist($query, $data)
    {

        return (   $this->scopeCountUserAnswerSet($query, $data) > 0) ? true : false ;
    }

    public function scopeCountUserAnswerSet($query, $data)
    {
       return DB::table($this->getTable())
            ->select('count(*)')
            ->where('user_quiz_id', $data['user_quiz_id'])
            ->where('question_id', $data['question_id'] )
            ->count();

    }

    public function scopeUpdateUserAnswerSet($query, $data)
    {

        DB::table($this->getTable())
            ->where('user_quiz_id', $data['user_quiz_id'])
            ->where('question_id', $data['question_id'] )
            ->update(
                [
                    'user_answer_set' => $data['user_answer_set'],
                    'is_valid' =>  $this->isValid($data['question_id'], $data['user_answer_set'] )
                ]
            );

    }

    public function scopeGetUserAnswerSet($quesry, $data)
    {

     return  DB::table($this->getTable())
            ->select('user_answer_set')
            ->where('user_quiz_id', $data['user_quiz_id'])
            ->where('question_id', $data['question_id'] )
            ->get()->first()->user_answer_set;

    }


    public function lastQuestionAnswered($userQuizId){

        return DB::table($this->getTable())
            ->select('question_id')
            ->where('user_quiz_id','=', $userQuizId)
            ->get()
            ->last()
            ->question_id;
    }


    public function nrOfQuestionAnswered($userQuizId)
    {
        return DB::table($this->getTable())
            ->where('user_quiz_id','=', $userQuizId)
            ->count();
    }

    public function scopeNrOfCorrectQuestionsAnswered($query, $userQuizId)
    {
        return DB::table($this->getTable())
            ->where('user_quiz_id','=', $userQuizId)
            ->where('is_valid','=', 1)
            ->count();
    }

    public function scopeNrOfIncorrectQuestionsAnswered($query, $userQuizId)
    {
        return DB::table($this->getTable())
            ->where('user_quiz_id','=', $userQuizId)
            ->where('is_valid','=', 0)
            ->count();
    }



}
