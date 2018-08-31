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



    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->quiz_id = 1;
        $this->user_id = 1;

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

        factory(\App\UserQuiz::class)->create(array('quiz_id'=>1, 'user_id' => 1));

        factory(\App\Question::class, 5)->create(array('quiz_id'=>1));

    }

    public function test_user_quiz_exist(){



        $this->assertCount(1, \App\UserQuiz::all());
        $incomplete =  ( new \App\UserQuiz)->quizIsIncomplete($this->user_id, $this->quiz_id);

        $this->assertTrue($incomplete);
    }

    public function test_get_incomplete_user_quiz_id()
    {

        factory(\App\UserQuiz::class)->create(array('quiz_id'=>2, 'user_id' => 1));

        $userQuizId =  ( new \App\UserQuiz)->getIncompleteUserQuizId($this->user_id, $this->quiz_id);

        $this->assertEquals(1, $userQuizId);

    }

    public function test_get_last_question_answered_from_incomplete_quiz()
    {

        factory(\App\UserQuiz::class)->create(array('quiz_id'=>1, 'user_id' => 1));
        $userQuizId =  ( new \App\UserQuiz )->getIncompleteUserQuizId($this->user_id, $this->quiz_id);

        $lastQuestionAnsweredId = (new \App\UserAnswerSet)->lastQuestionAnsweredId($userQuizId);

        $this->assertEquals(1, $lastQuestionAnsweredId);

    }

    public function test_get_next_question()
    {


        $userQuizId =  ( new \App\UserQuiz )->getIncompleteUserQuizId($this->user_id, $this->quiz_id);

        $this->assertEquals(5, \App\Question::all()->count());

        $lastQuestionAnsweredId = (new \App\UserAnswerSet)->lastQuestionAnsweredId($userQuizId);
        $nextQuestionId =  (new \App\Question)->nextQuestionId($lastQuestionAnsweredId, $this->quiz_id);
        $this->assertEquals( 3 , $nextQuestionId);


    }


    public function test_nr_of_question_answered()
    {
        $userQuizId =  ( new \App\UserQuiz )->getIncompleteUserQuizId($this->user_id, $this->quiz_id);

        $this->assertEquals(2, (new \App\UserAnswerSet)->nrOfQuestionAnswered($userQuizId));
    }

    public function test_nr_of_question_by_quiz_id()
    {

        $this->assertEquals(5, (new \App\Question)->nrOfQuestionByQuizId($this->quiz_id));
    }


    public function test_user_quiz_progress()
    {

        $totalNrOfQuestions = 5;
        $nrOfQuestionsAnswered = 2;
        $this->assertEquals(40, (new \App\Quiz)->quizProgress($totalNrOfQuestions, $nrOfQuestionsAnswered ) );


    }


    //getLastQuestionAnswered
    //getNextQuestion
    //totalNrOfQuestionAnswered
    //totalNrOfQuestions
    //userQuizProgress


    //$quiz_Id = 1;

}