<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Quiz;
use \App\Question;
use Illuminate\Pagination\Paginator;


class QuizzesController extends Controller
{
    public function homePage()
    {
            $quizzes = Quiz::all();

            if( !$quizzes || $quizzes->count() == 0) {

                return view('pages.home')->withErrors( ["errors"=>["Are no Quizzess in this moment"]]);
            }

            return view('pages.home', ["quizzes"=>$quizzes]);
    }


    public function addUserAnswer(Request $request)
    {

        $quiz_id = $request->quiz_id;


        try{
            Quiz::findOrFail($quiz_id);

        }catch( ModelNotFoundException $Ex){

            return back()->withErrors(array('errors' =>"Invalid Quiz requested"));
        }



    }

    public function quizDetails(Request $request)
    {


        $quiz = Quiz::find($request->quiz_id);
        $questions = Question::where( 'quiz_id','=',$request->quiz_id )->paginate( 1 );

        if(!isset($request->page)){
             $currentPage = $this->getCurrentPage();
        }
        $currentPage = $this->getCurrentPage( $request->page);

          if( !$quiz || empty($quiz)) {
               return view('pages.quizDetails')->withErrors( array("errors" => ["The quiz requested is unavailable"]));
          }

        return view('pages.quizDetails',
            array(
                'quiz'=> $quiz,
                'questions' =>  $questions,
            )
        );
    }

    public function getCurrentPage($page = null)
    {
        if($page == null){
            return $currentPage = 1;
        }else{
            return $page;
        }
    }

    public function getNextPage($paginator)
    {
        if($paginator->lastPage() == $this->getCurrentPage()){
            return null;
        }

        return $this->getCurrentPage() + 1;
    }

    public function getPreviousPage($paginator)
    {
        if( $this->getCurrentPage() == 1 ){
            return null;
        }

        return $this->currentPage()-1;
    }





}
