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
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

             $this->call('POST', '/addUserAnswer', ['quiz_id'=>131212])
                  ->assertStatus(302);

              $response = $this->followingRedirects();
              $response->contains('"Invalid Quiz requested"');
    }

    public function test_if_question_id_is_invalid_user_is_redirected_with_errors(){

        $this->call('POST', '/addUserAnswer', ['question_id'=>131212])
            ->assertStatus(302);

        $response = $this->followingRedirects();
        $response->contains('"Invalid Quiz requested"');
    }

    public function test_middleware_redirect_user_to_loginpage_when_not_loggedin()
    {

        Auth::logout();
        $request = Request::create('quiz.test', 'GET');
        $middleware = new \App\Http\Middleware\AuthMiddleware();
        $response = $middleware->handle($request, function() {  });
        $this->assertEquals($response->getStatusCode(),302);
        //https://matthewdaly.co.uk/blog/2016/11/29/testing-laravel-middleware/
    }

    public function test_if_user_submit_answer_without_an_answer_show_error_and_redirect()
    {
            $this->call('POST', '/addUserAnswer', array(
                'quiz_id'=>1,
                'question_id' =>1,
                'answer'=>[0,0]
            ))->assertStatus(302);
            $response = $this->followingRedirects();
            $response->contains("Invalid answer. Empty answer");

    }

    public function test_set_user_answer_form()
    {
       $input = array('user_id'=>1, 'question_id'=> 1, 'user_quiz_id'=>1,'answers'=> [0,1]);
       $output =  (new \App\UserAnswerSet)->setUserAnswer($input);
       $this->assertSame('01', $output);

    }

    public function test_when_user_quiz_start_new_user_quiz_is_created()
    {
        $input = array(
            'user_id' => 1,
            'quiz_id' => 1
        );

        $output = 1; //UserQuiz->id

        $output =  (new \App\UserQuiz)->init($input);
        $this->assertSame(1, $output);

    }


}
