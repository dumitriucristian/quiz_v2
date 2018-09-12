<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Result;
class UserQuizResultsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->quiz_id = 1;
        $this->user_id = 1;

        factory(\App\UserAnswerSet::class)->create(

            array(
                'user_quiz_id' => 2,
                'question_id' => 1,
                'user_answer_set' => '10',
                'is_valid' => 1
            )
        );

        factory(\App\UserAnswerSet::class)->create(
            array(
                'user_quiz_id' => 1,
                'question_id' => 1,
                'user_answer_set' => '10',
                'is_valid' => 1
            )
        );

        factory(\App\UserAnswerSet::class)->create(
            array(
                'user_quiz_id' => 1,
                'question_id' => 2,
                'user_answer_set' => '10',
                'is_valid' => 0
            )
        );

        factory(\App\UserAnswerSet::class)->create(
            array(
                'user_quiz_id' => 1,
                'question_id' => 3,
                'user_answer_set' => '10',
                'is_valid' => 1
            )
        );

        factory(\App\UserAnswerSet::class)->create(
            array(
                'user_quiz_id' => 1,
                'question_id' => 4,
                'user_answer_set' => '10',
                'is_valid' => 0
            )
        );

        factory(\App\UserQuiz::class)->create(array('quiz_id' => 1, 'user_id' => 1));
        factory(\App\Quiz::class,2)->create();
        factory(\App\Question::class, 2)
            ->create()
            ->each(function($question){
                factory(\App\QuestionQuiz::class)
                    ->create(array('quiz_id'=>1,'question_id'=> $question->id) );
            });

    }
    public function test_user_end_quiz_completing_date_is_registered()
    {
        $user_quiz_id = 1;
        (new \App\UserQuiz)->quizIsComplete( $user_quiz_id );
        $isCompleted = \App\UserQuiz::where('completed_at', '<>', null)->count();
        $this->assertEquals(1, $isCompleted);

    }



    public function test_user_get_quiz_results()
    {

        $user_quiz_id = 1;


        $results = (New \App\Result)->setQuizResult($user_quiz_id);

        $this->assertArrayHasKey('user_quiz_id',  $results);
        $this->assertArrayHasKey('user_id',  $results);
        $this->assertArrayHasKey('quiz_id',  $results);
        $this->assertArrayHasKey('nr_of_questions_answered',  $results);
        $this->assertArrayHasKey('nr_of_correct_answers',  $results);
        $this->assertArrayHasKey('nr_of_incorrect_answers',  $results);

    }

    public function test_user_save_quiz_result()
    {

          $quizResult =  array(
            'user_quiz_id' => 1,
            'user_id' => 1,
            'quiz_id' => 1,
            'nr_of_questions' => 2,
            'nr_of_questions_answered' => 2,
            'nr_of_correct_answers' => 2,
            'nr_of_incorrect_answers' => 0,
             'successRatio' => \App\Result::successRatio(2,2)
            );

          \App\Result::saveResult( $quizResult);

          $this->assertEquals(1, \App\Result::all()->count());
    }

    public function test_success_ratio()
    {
        $nrOfQuestions = 4;
        $nrOfCorrectAnswers = 2;

        $this->assertEquals(50, Result::successRatio($nrOfQuestions, $nrOfCorrectAnswers));
    }


}