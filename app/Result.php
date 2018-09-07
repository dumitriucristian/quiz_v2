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
        'nr_of_incorrect_answers',
        'successRatio'
    ) ;

    public function scopeSetQuizResult($query, $user_quiz_id)
    {

        $quiz_id = UserQuiz::find($user_quiz_id)->quiz_id;
        $nrOfCorrectAnswers = UserAnswerSet::nrOfCorrectQuestionsAnswered($user_quiz_id);
        $nrOfQuestions  = (new \App\Question)->nrOfQuestionByQuizId( $quiz_id );

        return array(
            'user_quiz_id' => $user_quiz_id,
            'user_id' => Auth::user()->id,
            'quiz_id' => $quiz_id,
            'nr_of_questions' => $nrOfQuestions,
            'nr_of_questions_answered' => (new UserAnswerSet)->nrOfQuestionAnswered($user_quiz_id),
            'nr_of_correct_answers' => $nrOfCorrectAnswers,
            'nr_of_incorrect_answers' => UserAnswerSet::nrOfIncorrectQuestionsAnswered($user_quiz_id),
            'successRatio' => $this->scopeSuccessRatio($query, $nrOfQuestions, $nrOfCorrectAnswers)
        );
    }

    public function scopeSaveResult( $query, $quizResult )
    {
        DB::table($this->getTable())->insert($quizResult);

    }

    public function scopeSuccessRatio($query, $nrOfQuestions, $nrOfCorrectAnswers)
    {


        if($nrOfQuestions == 0 || $nrOfCorrectAnswers == 0){
            return 0;
        }
        return (int) $nrOfCorrectAnswers * 100 / $nrOfQuestions;
    }

}
