<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use \App\User;
use \App\Quiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;


class QuizPageTest extends TestCase
{

    use DatabaseMigrations;

    protected static $quiz = false;

    public function setUp()
    {
        parent::setUp();

            static::$quiz = factory(Quiz::class, 1)
                ->create()
                ->each(function(Quiz $quiz){
                    factory(\App\Question::class, 3)
                        ->create(['quiz_id'=> $quiz->id]);
                }
            );

    }

    public function test_quiz_id_exist()
    {

       $this->assertInstanceOf(Collection::class, static::$quiz);

       $request = $this->call('GET','/quiz/{id}', ['id' => 1])
           ->assertViewIs('pages.quizDetails')
           ->assertStatus(200);
    }


    public function test_quiz_page_with_invalid_id_throw_error()
    {

        $response = static::$quiz->find(2);
        $this->assertNull( $response );
        $response  = $this->get('quiz/2')->assertSee('The quiz requested is unavailable');

    }

    public function test_quiz_has_next_question()
    {

       $quiz = static::$quiz->first();
       $questions =     $quiz->questions;
       $this->assertCount(3, $questions);
    }

    public function user_saves_answer()
    {
        //given a user send an answer

        //and the answer has a valid user_id, a question_id and a valid quiz_id
        //then we create a formated user_answer_set
        //then formated answer is saved into the formated_user_answer table
        //and each answer is saved into the user_answer table
        //if is not the last question user is sent to the next question
        //if is the last question user in sent to the result page
        //where the user can see the assessment page


    }


    public function test_if_quiz_id_is_invalid_user_is_redirected_with_errors(){

             $response = $this->call('POST', '/addUserAnswer', ['quiz_id'=>131212])
              ->assertStatus(302);

             // $response->assertSee('Invalid Quiz requested');

    }



    public function user_send_answer()
    {
        //$this->call("POST",'addUserAnswer')->assertStatus(200);
        $answers = array(
                    14 => 0,
                    15 => 0
                );


        //check if valid quiz_id
        //check if valid
    }







}
