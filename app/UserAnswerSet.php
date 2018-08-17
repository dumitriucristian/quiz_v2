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
        return ( \App\QuestionValidAnswerSet::getValidAnswerSetByQuestionId($question_id)  == $answer) ? TRUE : FALSE ;
        // and compare with the current answer

    }

    public function scopeUserAnswerSetExist($query, $data)
    {
      $nrOfResults =  DB::table($this->getTable())
                    ->select('count(*)')
                    ->where('user_quiz_id', $data['user_quiz_id'])
                    ->where('question_id', $data['question_id'] )
                    ->count();


      return ($nrOfResults > 0) ? true : false ;
    }
}
