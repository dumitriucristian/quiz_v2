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
    
    //$quiz_Id = 1;

}
