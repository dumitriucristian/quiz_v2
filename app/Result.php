<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Result extends Model
{

    protected $fillable = array(
        'user_quiz_id',
        'user_id',
        'quiz_id',
        'nr_of_questions',
        'nr_of_questions_answered',
        'nr_of_correct_answers',
        'nr_of_incorrect_answers'
    ) ;

    public function scopeSetQuizResult($query, $user_quiz_id)
    {

        $quiz_id = UserQuiz::find($user_quiz_id)->quiz_id;

        return array(
            'user_quiz_id' => $user_quiz_id,
            'user_id' => Auth::user()->id,
            'quiz_id' => $quiz_id,
            'nr_of_questions' => (new \App\Question)->nrOfQuestionByQuizId( $quiz_id ),
            'nr_of_questions_answered' => (new UserAnswerSet)->nrOfQuestionAnswered($user_quiz_id),
            'nr_of_correct_answers' => UserAnswerSet::nrOfCorrectQuestionsAnswered($user_quiz_id),
            'nr_of_incorrect_answers' => UserAnswerSet::nrOfIncorrectQuestionsAnswered($user_quiz_id)

        );
    }

    public function scopeSaveResult( $query, $quizResult )
    {
        DB::table($this->getTable())->insert($quizResult);

    }


}
