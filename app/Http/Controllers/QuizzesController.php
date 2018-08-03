<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Quiz;
use \App\Question;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;


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
        $question_id = $request->question_id;

        try{
            Quiz::findOrFail($quiz_id);
            Question::findOrFail($question_id);

        }catch( ModelNotFoundException $Ex){

            return back()->withErrors(array('errors' =>"Invalid Quiz requested"));
        }

        $answers = $request->answer;

        if(in_array(1,$answers) === false ){
            return back()->withErrors(array('errors' =>"Invalid answer. Empty answer"));
        }
        $data = ['user_id' => Auth::User()->id, 'quiz_id'=>$quiz_id ];
        $userQuiz = new \App\UserQuiz;
        if($userQuiz->findUserQuiz($data) == 0){

            $userQuiz->quiz_id = $quiz_id;
            $userQuiz->user_id = Auth::User()->id;
            $userQuiz->completed_at = date('Y-m-d H-i-s', time());

            $s = $userQuiz->save();

        };





    }

    public function quizDetails(Request $request)
    {


        $quiz = Quiz::find($request->quiz_id);
        $questions = Question::where( 'quiz_id','=',$request->quiz_id )->paginate( 1 );
        //dd($questions->currentPage());
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
