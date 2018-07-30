<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use \App\User;
use \App\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuizPageTest extends TestCase
{

    use DatabaseMigrations;
    /*
    given there is a quiz that has many questions
    when user visit quiz page
    see quiz quiz questions and possible answers
    */

    /**
     *@test
     */
    public function quiz_id_exist()
    {

        $quiz = factory(Quiz::class, 1)
            ->create()
            ->each(function(Quiz $quiz){
                factory(\App\Question::class, 3)
                    ->create(['quiz_id'=> $quiz->id]);
            });

        $this->assertInstanceOf(Collection::class, $quiz);

       $request = $this->call('GET','/{id}/quiz', ['id' => 1])
           ->assertViewIs('pages.quizDetails')
           ->assertStatus(200);
          // ->assertSee($quiz->first()->description)
          // ->assertSee($quiz->first()->questions->first()->body);
//
    }


    /**
     * @test
     */
    public function quiz_page_with_invalid_id()
    {

        /* given  a user visit quiz page
        when the id provided is invalid
        then an exception should be thrown and  show error message*/

        $quiz = factory(Quiz::class, 1)
            ->create()
            ->each(function(Quiz $quiz){
                factory(\App\Question::class, 3)
                    ->create(['quiz_id'=> $quiz->id]);
            });
        $response = $quiz->find(2);
        $this->assertNull( $response );
        $response  = $this->get('/2/quiz')->assertSee('The quiz requested is unavailable');


    }

    public function test_user_see_first_current_previous_questin()
    {
        $quiz = factory(Quiz::class, 1)
            ->create()
            ->each(function(Quiz $quiz){
                factory(\App\Question::class, 3)
                    ->create(['quiz_id'=> $quiz->id]);
                });

        $answers = factory(\App\Answer::class,3)->create();

        $quizess = \App\Quiz::all();
        $questions = \App\Question::all();
        $answers = \App\Answer::all();


        $this->assertCount( 4, $quizess);
        $this->assertCount(6, $questions);
      //  $this->assertTrue(false);

    }
}
