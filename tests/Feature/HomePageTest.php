<?php

namespace Tests\Feature;

//use App\Http\Kernel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use \App\Quiz;
use \App\User;

class HomePageTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * @test
     */

    public function show_list_of_quizzes_on_the_homepage()
    {
        /*
            given a list of quizzes exist
            when  a user visit the homepage
            then  the user can see a list of quizzess
      */

       $quizzes = factory(Quiz::class,5)->create();
        $this->assertInstanceOf(Collection::class, $quizzes);
        $this->assertEquals(5, $quizzes->count());

        $response = $this->get('/home')->assertStatus(200)
            ->assertViewIs('pages.home')
            ->assertSee($quizzes->first()->description);


    }


    /**
     * @test
     */
    public function homeroute_no_quizzes_on_database()
    {
        if(Quiz::all()->count()==0){
            $this->get('/')->assertSee('You can add a new quiz');
        }

    }






}
