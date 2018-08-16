<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAnswerSet extends Model
{

    protected $guarded = array('id');

    public function scopeSetUserAnswer($query, $data)
    {
        return $answer = implode('',$data['answers']);

    }

    public function scopeSaveAnswerSet($query, $data)
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
}
