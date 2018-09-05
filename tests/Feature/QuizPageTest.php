<?php

namespace Tests\Feature;

use App\QuestionValidAnswerSet;
use App\UserAnswerSet;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use \App\User;
use \App\Quiz;
use \App\UserQuiz;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $url = url()->current('/');
        $request = Request::create($url, 'GET');
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
       $input = array( 0, 1);
       $output =  \App\UserAnswerSet::setUserAnswer($input);
       $this->assertSame('01', $output);

    }

    public function test_user_quiz_is_created()
    {
        $input = array(
            'user_id' => 1,
            'quiz_id' => 1
        );

        $output = 1; //UserQuiz->id

        $output =  UserQuiz::init($input);
        $this->assertSame(1, $output);

    }

    public function test_user_can_see_quiz_completion_status_in_progress()
    {

        //quiz may be started, finished,
        $response = $this->call('GET','/')->assertStatus(200);
        $response->assertSeeText('Status: In Progress');
    }

    public function if_user_has_not_accesessed_quiz_never_try_this_quiz_status_showed(){

        $response = $this->call('GET','/')->assertStatus(200);

        $response->assertSeeText('Try This Quiz');
    }


    public function test_user_quiz_is_created_when_user_answer_first_question()
    {

       factory(\App\UserQuiz::class, 1)->create();

        $userQuiz = UserQuiz::first();

        $data = array(
            'user_id' =>  $userQuiz->user_id,
            'quiz_id' => $userQuiz->quiz_id
        );

        $user_quiz = UserQuiz::findUserQuiz($data);

        $this->assertEquals(1, $user_quiz);

    }


    public function test_user_has_no_records_in_user_quiz_table()
    {
          $data = array(
              'user_id'=>1,
              'quiz_id'=>1
          );

          $user_quiz = UserQuiz::findUserQuiz($data);
          $this->assertEquals(0, $user_quiz);
    }


    public function test_user_should_be_redirected_to_next_question()
    {
        factory(UserQuiz::class, 1)->create();

        $userQuiz = UserQuiz::first();

        $data = array(
            'user_id' =>  $userQuiz->user_id,
            'quiz_id' => $userQuiz->quiz_id
        );

        $user_quiz = UserQuiz::findUserQuiz($data);
        $this->assertEquals(1, $user_quiz);
        $this->call('POST','/addUserAnswer', $data)->assertStatus(302);

    }



    public function test_answer_set_is_made_when_user_submit_answer()
    {
        $input = array(
              0=>0,
              1=>1
        );

        $output = '01';
        $response = \App\UserAnswerSet::setUserAnswer($input);
        $this->assertEquals($response, $output);
    }


    public function test_user_can_save_answer_set()
    {

        $nextPage = "quiz/1?page=2";

        $answer = array(
            "user_id" => 1,
            "quiz_id" => 1

        );

        $answer =  factory(UserQuiz::class, 1)->create($answer);

        $answerSetData = array(

            'user_quiz_id'=>$answer->first()->id,
            'question_id' => 1,
            'user_answer_set' =>'10',
            'is_valid'=>TRUE
        );

        $answerSet = factory(\App\UserAnswerSet::class,1)->create($answerSetData);

        $this->assertEquals(1, $answerSet->count());

    }

    public function test_AnswerSet_save_answer_set(){

        $validAnswer = array(
            'id'=>1,
            'question_id'=>1,
            'valid_answer' =>'10'
        );

        factory(\App\QuestionValidAnswerSet::class,1)->create($validAnswer);

        $answer = array(

            "question_id" => 1,
            "user_quiz_id" => 1,
            "user_answer_id" => 1,
            "user_answer_set" => '10',
            'answers' => array('1'=>'1', '2'=>'0')

        );

        \App\UserAnswerSet::SaveUserAnswerSet($answer);

        $this->assertEquals( 1, \App\UserAnswerSet::all()->count() );
    }

    public function test_question_has_valid_answer()
    {
        $data = array(
            'id'=>1,
            'question_id'=>1,
            'valid_answer' =>'10'
        );

        factory(\App\QuestionValidAnswerSet::class,1)->create($data);

        $this->assertEquals('10', \App\QuestionValidAnswerSet::getValidAnswerSetbyQuestionId(1));
    }

    public function test_if_answer_set_already_exist_update_answer()
    {

        $questionValidAnswerData = array(
            'question_id' => 1,
            'valid_answer'=>'10',

        );
        factory(QuestionValidAnswerSet::class, 1 )->create($questionValidAnswerData);

        $data = array(
            'user_quiz_id'=>1,
            'question_id'=>1,
            'user_answer_set' =>'10',
             'is_valid'=>true
        );

        factory(UserAnswerSet::class,1)->create($data);

        $newData = array(

            'user_quiz_id'=>1,
            'question_id'=>1,
            'user_answer_set' =>'01'
        );

        $this->assertTrue( UserAnswerSet::userAnswerSetExist($newData));

        //can exist only one answer with the same question_id and user_quiz_id
        $this->assertEquals(1, UserAnswerSet::countUserAnswerSet($newData));

        UserAnswerSet::updateUserAnswerSet($newData);

        $this->assertEquals('01', UserAnswerSet::getUserAnswerSet($newData));

    }

    public function test_each_userAnswer_has_answer()
    {
        $answers = array(
            1 => "1",
            2 => "0"
        );

        foreach($answers as $key => $value){
            $answer = array(
                "user_quiz_id" => 1,
                "question_id" => $key,
                "answer_id"=>1,
                "user_answer" => $value,
                "is_valid" => 1
            );

            factory(\App\UserAnswer::class)->create($answer);
        }

       $this->assertEquals(2, \App\UserAnswer::all()->count());

    }


    public function test_when_user_send_answer_each_answer_is_saved()
    {
        $userData =array(
            "quiz_id" => "1",
            "nextPage" => "http://homestead.test/quiz/1?page=2",
            "question_id" => "1",
                "answer" => array(
                    1 => "1",
                    2 => "0"
                )
            );

        \App\UserAnswer::saveEachUserAnswer($userData);

        $this->assertEquals(2, \App\UserAnswer::all()->count() );

    }


    public function test_user_answer_is_valid()
    {

        $answer = factory(\App\Answer::class)->create(   array(
            'question_id' => 1,
             'correct' => 1
            )
        );

        $answerId = 1;
        $answerValue = 1;

        $this->assertTrue(\App\UserAnswer::isValid($answerId, $answerValue));

    }

    public function test_user_answer_is_not_valid()
    {

        $answer = factory(\App\Answer::class)->create( array(
                'question_id' => 1,
                'correct' => 0
            ));

        $answerId = 1;
        $answerValue = 1;

        $this->assertFalse(\App\UserAnswer::isValid($answerId, $answerValue));

    }




}
