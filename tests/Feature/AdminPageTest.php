<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;

class AdminPageTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */
    public function admin_create_new_quiz()
    {
        $this->get('/admin/addQuiz')->assertStatus(200);
    }

    /**
     * @test
     */
    public function admin_save_new_quiz()
    {
        /*
        given a user
        when hit the saveQuiz Button
        then data is sent to saveQuiz method
        title and description are checked to be obligator
        after save user is redirected to add question and answers form with quiz data preloaded
        */
        $newQuizData = [
            "title" => "PHP",
            "description"      => "Code free"
        ];

        $newQuiz = factory(\App\Quiz::class)->make($newQuizData);

        $response = $this->post('/admin/saveQuiz', $newQuizData )
            ->assertRedirect('/admin/addQuestion?quizId=1')
            ->assertStatus(302);

    }

    public function test_user_add_question()
    {

        // Given a quiz id
        $quizzes = \App\Quiz::all();
        $this->assertCount(0, $quizzes);

        $quizzes = factory(\App\Quiz::class,1)->create();
        $this->assertCount(1, $quizzes);
        $quizId = \App\Quiz::all()->first()->id;

        // then he add a new question to the quiz
        $questions = \App\Question::all();
        $this->assertCount(0, $questions);

        //when the user visit addQuestion page
        $response = $this->post('/admin/saveQuestion', ["quizId"=>$quizId, "question"=>"test"]);

        $newQuestions = \App\Question::all();

        $this->assertInstanceOf(Collection::class, $newQuestions);
        $this->assertCount(1, $newQuestions);



        // and can see the question on the quiz admin page
        // $this->get('/admin/addQuestion?quizId='.$quizId)->assertSee("someQuestion");
    }

    public function test_display_error_when_invalid_quiz_id()
    {
        $quizId = 100; //invalid id
        $quiz = \App\Quiz::find($quizId);
        $this->assertNull($quiz);

        if(empty($quiz)){
            $response = $this->get('/admin/addQuestion?quizId='.$quizId)
            ->assertSee('Sorry');
         }
    }



    public function test_user_delete_question()
    {
        // Given a quiz id
        $quiz = factory(\App\Quiz::class,1)->create();
        $this->assertInstanceOf(Collection::class, $quiz);

        //given three questions
        $questions = factory(\App\Question::class,3)->create(['quiz_id'=>"1"]);

        $this->assertInstanceOf(Collection::class, $questions);
        $this->assertCount(3, $questions);

        //user delete one questions
        $response =  $this->get('admin/'.$questions->first()->id.'/'.$quiz->first()->id.'/removeQuestion');
        $remainedQuestions = \App\Question::all();

        //one question should remain
        $this->assertCount(2, $remainedQuestions);

    }

    public function test_user_update_question()
    {
        //given a question id exist
        $quiz = factory(\App\Quiz::class,1)->create();


        $question = factory(\App\Question::class, 1)->create(array(
            'quiz_id'=>$quiz->first()->id,
            'body'=>"Ceva"
            )
        );
        $questions = \App\Question::all();
        $this->assertCount( 1, $questions);

        $response = $this->post('/admin/updateQuestion',array(
            "quizId" => $quiz->first()->id,
            "questionId" => $questions->first()->id,
            "body"=>"TOLOMAC"
        ));

        $newQuestions =  \App\Question::all();
        $this->assertCount( 1, $newQuestions);
        $response->assertSee("TOLOMAC");
    }

    public function test_user_add_answer()
    {
        $quiz = factory(\App\Quiz::class,1)->create();

        $question = factory(\App\Question::class,1)->create(
            array(
                'quiz_id'=>$quiz->first()->id
            )
        );

        $this->assertCount(1, $question);


        $this->post('/admin/addAnswer',
            array(
                'questionId' => $question->first()->id,
                'text'  => "TEST",
                'correct' => 0
            )
        );
    }


    public function test_user_see_administrable_quizz(){
        //given there are quizzes
        $quizzes = factory(\App\Quiz::class,5)->create();
        //when visit admin/quizzzes
        $this->get('/admin/quizzes')->assertStatus(200);
        //then show all the quizzes with a link toward edit quiz page
        $this->assertCount(5, $quizzes);


    }

    public function test_question_body_can_not_be_empty()
    {
        //given a user add question to a quiz
        $quiz = factory(\App\Quiz::class,1)->create();
        $this->assertInstanceOf(Collection::class, $quiz);
        $this->assertCount(1, $quiz);
        //when empty body is sented

        //then error must return

    }

    public function test_user_add_answer_to_question()
    {
        //given a question that
        $question = factory(\App\Question::class, 1)->create();
        $this->assertInstanceOf(Collection::class, $question);
        $this->assertCount(1, $question);
        //when user visit edit questin - add answer
        $response =  $this->post('/admin/saveAnswer',
                array(
                    'questionId' => $question->first()->id,
                    'correct'   => 1,
                    'body'      => "this is a test answer"
                )
            );
        //a new answer is added
        $questions = \App\Question::all();

         $this->assertCount(1, $questions);

        $question = $questions->first();
        $answer  = $question->answer ;

        $this->assertCount(1,$answer);
        //and valid_answer_set_is_created
        $this->valid_answer_set_is_created($question);

        return $answer;
    }

    public function user_update_question_answer()
    {


    }

    public function user_remove_question_answer()
    {

    }

    public function valid_answer_set_is_created($question)
    {
        $data = array(
            'question_id' => $question->id,
            'correct'     => 1,
            'body'       =>  "test Answer"
        );

        $this->post('/admin/saveAnswer', $data);

        $answers = \App\Answer::all();
        $this->assertCount(1, $answers);

        $questionValidAnswerSet = \App\QuestionValidAnswerSet::all();
        $this->assertCount(1, $questionValidAnswerSet);

    }

}
