<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Result extends Model
{
    public function setQuizResult( $user_quiz_id)
    {


        return array(
            'user_quiz_id' => $user_quiz_id,
            'user_id' => Auth::user()->id,
            'quiz_id' => UserQuiz::find($user_quiz_id)->pluck('quiz_id'),
            'nr_of_questions_answered' => (new UserAnswerSet)->nrOfQuestionAnswered($user_quiz_id),
            'nr_of_correct_answers' => UserAnswerSet::nrOfCorrectQuestionsAnswered($user_quiz_id),
            'nr_of_incorrect_answers' => UserAnswerSet::nrOfIncorrectQuestionsAnswered($user_quiz_id)

        );
    }
}
