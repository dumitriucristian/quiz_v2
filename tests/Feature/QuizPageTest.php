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

       $request = $this->call('GET','/{id}/quiz', ['id' => 1])
           ->assertViewIs('pages.quizDetails')
           ->assertStatus(200);
    }


    public function test_quiz_page_with_invalid_id_throw_error()
    {

        $response = static::$quiz->find(2);
        $this->assertNull( $response );
        $response  = $this->get('/2/quiz')->assertSee('The quiz requested is unavailable');

    }



    public function test_quiz_has_next_question()
    {
        $quiz = static::$quiz->first();

        $questions =     $quiz->questions;
      //dd($questions);
        $this->assertCount(3, $questions);
    }
    //count nr of questions
    //if quiz has only one question no pagination is required
    //if quiz has more questions
        //get quiz model first question data
        //get  quiz model  the next question id







}
