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
        $questions = factory(\App\Question::class,3)->create();

        $this->assertInstanceOf(Collection::class, $questions);
        $this->assertCount(3, $questions);

        //user delete one questions
        $response =  $this->get('admin/'.$questions->first()->id.'/'.$quiz->first()->id.'/removeQuestion');
        $remainedQuestions = \App\Question::all();

        //one question should remain
        $this->assertCount(2, $remainedQuestions);

    }

    public function test_user_add_answer()
    {
        $quiz = factory(\App\Quiz::class,1)->create();

        $question = factory(\App\Question::class,1)->create( );

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

    public function test_admin_add_categories()
    {
        $this->assertEquals(0, \App\Category::all()->count());
        $this->call('post','admin/addCategory',  array('name'=>'php'));
        $this->assertEquals(1, \App\Category::all()->count() );


    }

    public function test_admin_add_tags()
    {
        $this->assertEquals(0, \App\Tag::all()->count());
        $this->call('post','admin/addTag',  array('name'=>'php'));
        $this->assertEquals(1, \App\Tag::all()->count() );


    }

    public function test_admin_add_tags_to_questions()
    {
        $question = factory(\App\Question::class, 1)->create();
        $this->assertEquals(0, \App\Question::find(1)->tags()->count());
        $this->call('post','admin/addTag',  array('name'=>'php'));
        $this->call('post','admin/addTag',  array('name'=>'javascript'));

        $questionId = 1;
        $tags_id = array(1,2);

        (new \App\TagQuestion)->appendTags($questionId, $tags_id);
        $this->assertEquals(2, \App\Question::find(1)->tags()->count());

    }


    public function test_admin_question_tag()
    {
        $question = factory(\App\Question::class, 1)->create();
        $this->assertEquals(0, \App\Question::find(1)->tags()->count());
        $this->call('post','admin/addTag',  array('name'=>'php'));
        $this->call('post','admin/addTag',  array('name'=>'javascript'));

        $questionId = 1;
        $tags_id = array(1,2);
        $tag_id= 2;

        (new \App\TagQuestion)->appendTags($questionId, $tags_id);
        $this->assertEquals(2, \App\Question::find(1)->tags()->count());

        (new \App\TagQuestion)->removeTag($questionId, $tag_id);
        $this->assertEquals(1, \App\Question::find(1)->tags()->count());

    }

    public function test_admin_add_category(){

        $categoryId = 1;
        $questionId = 1;
        $category = factory(\App\Category::class)->create(array('id' => 1));
        $question = factory(\App\Question::class)->create();
        \App\Question::find(1)->addCategory($categoryId, $questionId);

        $this->assertEquals(1, \App\Question::find(1)->category->id);
    }


}
