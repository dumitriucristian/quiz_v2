<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserQuizStatusTest extends TestCase
{
    //if user has previously answered to this quiz and has not completed the quiz

    //redirect user to the summary page
    // where he can choose to
    //reset the quiz or
    // he can continue from last question


    //given user id and quiz id who had answered before
    //when user click on the quiz
    //check if user_id and quiz id exist into user_quizzes table and completed at is null
    public function test_user_quiz_exist(){

        $user_id=1;
        $quiz_id=1;

        factory(\App\UserQuiz::class)->create(array('quiz_id'=>1, 'user_id' => 1));

        $this->assertCount(1, \App\UserQuiz::all());
        $incomplete =  ( new \App\UserQuiz)->quizIsIncomplete($user_id, $quiz_id);

        $this->assertTrue($incomplete);
    }

    public function test_get_incomplete_user_quiz_id()
    {
        $user_id=1;
        $quiz_id=1;
        factory(\App\UserQuiz::class)->create(array('quiz_id'=>1, 'user_id' => 1));
        factory(\App\UserQuiz::class)->create(array('quiz_id'=>2, 'user_id' => 1));

        $userQuizId =  ( new \App\UserQuiz)->getIncompleteUserQuizId($user_id, $quiz_id);

        $this->assertEquals(1, $userQuizId);

    }

    public function test_get_last_question_answered_from_incomplete_quiz()
    {
        $user_id = 1;
        $quiz_id = 1;

        factory(\App\UserQuiz::class)->create(array('quiz_id'=>1, 'user_id' => 1));
        $userQuizId =  ( new \App\UserQuiz )->getIncompleteUserQuizId($user_id, $quiz_id);

        factory(\App\UserAnswerSet::class )->create(

                array(
                    'user_quiz_id' => 2,
                    'question_id' => 1,
                    'user_answer_set' =>'10',
                    'is_valid' => 1
                )
        );

        factory(\App\UserAnswerSet::class )->create(
                array(
                    'user_quiz_id' => 1,
                    'question_id' => 1,
                    'user_answer_set' =>'10',
                    'is_valid' => 1
                )
        );

        factory(\App\UserAnswerSet::class )->create(
                array(
                    'user_quiz_id' => 1,
                    'question_id' => 2,
                    'user_answer_set' =>'010',
                    'is_valid' => 0
                )

        );


        $lastQuestionAnsweredId = (new \App\UserAnswerSet)->lastQuestionAnsweredId($userQuizId);

        $this->assertEquals(2, $lastQuestionAnsweredId);

    }

    public function test_get_next_question()
    {

        $user_id = 1;
        $quiz_id = 1;

        factory(\App\UserQuiz::class)->create(array('quiz_id'=>1, 'user_id' => 1));
        $userQuizId =  ( new \App\UserQuiz )->getIncompleteUserQuizId($user_id, $quiz_id);

        factory(\App\UserAnswerSet::class )->create(

            array(
                'user_quiz_id' => 2,
                'question_id' => 1,
                'user_answer_set' =>'10',
                'is_valid' => 1
            )
        );

        factory(\App\UserAnswerSet::class )->create(
            array(
                'user_quiz_id' => 1,
                'question_id' => 1,
                'user_answer_set' =>'10',
                'is_valid' => 1
            )
        );

        factory(\App\UserAnswerSet::class )->create(
            array(
                'user_quiz_id' => 1,
                'question_id' => 2,
                'user_answer_set' =>'010',
                'is_valid' => 0
            )

        );

        factory(\App\Question::class, 5)->create(array('quiz_id'=>1));

        $this->assertEquals(5, \App\Question::all()->count());

        $lastQuestionAnsweredId = (new \App\UserAnswerSet)->lastQuestionAnsweredId($userQuizId);
        $nextQuestionId =  (new \App\Question)->nextQuestionId($lastQuestionAnsweredId, $quiz_id);
        $this->assertEquals( 3 , $nextQuestionId);


    }
    //getLastQuestionAnswered
    //getNextQuestion
    //totalNrOfQuestionAnswered
    //totalNrOfQuestions
    //userQuizProgress


    //$quiz_Id = 1;

}
