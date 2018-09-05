<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserQuizResultsTest extends TestCase
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


    }


    public function count_maxim_nr_of_quiz_points()
    {

    }

    public function user_get_one_point_for_each_correct_answer_set()
    {

    }

    public function count_nr_of_unanswered_questions()
    {

    }

    public function count_nr_of_good_answere()
    {

    }

    public function count_nr_bad_answer()
    {

    }



    public function test_is_last_question()
    {
        $quiz_id = 1;
        $question_id = 5;
        $this->assertTrue( (new \App\Question)->isLastQuestion($quiz_id, $question_id ) );
    }



}