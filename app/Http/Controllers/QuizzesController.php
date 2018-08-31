<?php

namespace App\Http\Controllers;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Quiz;
use \App\Question;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $userQuizData = array(
            'user_id' => Auth::User()->id,
            'quiz_id'=>$quiz_id,
            'question_id'=>$question_id
        );

        if(\App\UserQuiz::findUserQuiz($userQuizData) == 0){


            $userQuiz = new \App\UserQuiz;

            $userQuiz->quiz_id = $quiz_id;
            $userQuiz->user_id = Auth::User()->id;
            //todo completed at must be recorded when the user hit the submit quiz answers button;
           // $userQuiz->completed_at = date('Y-m-d H-i-s', time());

           $userQuiz->save();
        }else{

            $answerData = array(
                "question_id" => $question_id,
                "user_quiz_id" =>  \App\UserQuiz::findUserQuiz($userQuizData),
                "user_answer_set" => \App\UserAnswerSet::setUserAnswer($answers)
            );

            //insert
            if(\App\UserAnswerSet::userAnswerSetExist($answerData) == false){
                \App\UserAnswerSet::saveUserAnswerSet($answerData);
                \App\UserAnswer::saveEachUserAnswer($request);
            }

            //update
            if(\App\UserAnswerSet::userAnswerSetExist($answerData) == true) {
                \App\UserAnswerSet::updateUserAnswerSet($answerData);
                \App\UserAnswer::updateEachUserAnswer($request);
            }

        };


        if($request->nextPage != null){

            return redirect( $request->nextPage );
        }


        return back();
    }


    public function quizDetails(Request $request)
    {

       // find user_quiz_id to count how many questions user answered
        $quiz = Quiz::find($request->quiz_id);


        //check if quiz is finished

        $quizIsIncomplete = (new \App\UserQuiz)->quizIsIncomplete( Auth::user()->id, $request->quiz_id );

        if ( $quizIsIncomplete ) {

           $userQuizId =  (new \App\UserQuiz)->getIncompleteUserQuizId(Auth::user()->id, $request->quiz_id);
           $lastQuestionAnswered = (new \App\UserAnswerSet)->lastQuestionAnsweredId($userQuizId);
           $nrOfAnswers = (new \App\UserAnswerSet)->nrOfQuestionAnswered($userQuizId);
           $nrOfQuestions = (new \App\Question)->nrOfQuestionByQuizId($request->quiz_id);
           $userProgress = (new \App\Quiz)->quizProgress($nrOfQuestions, $nrOfAnswers );

           $data = array(
              "quiz_id" => $request->quiz_id,
              "user_id" =>Auth::user()->id,
              "user_quiz_id" => $userQuizId,
              "lastQuestionAnswered" =>$lastQuestionAnswered,
              "nextQuestion" => (new \App\Question)->nextQuestionId($lastQuestionAnswered, $request->quiz_id),
              "nrOfAnswers" => $nrOfAnswers,
              'totalNrOfQuestions'=> $nrOfQuestions,
              'userProgress' => $userProgress
          );


            //getLastQuestionAnswered
            //getNextQuestion
            //totalNrOfQuestionAnswered
            //totalNrOfQuestions
            //userQuizProgress




          dd($data);

          return view('page.quizSummary');
        }


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
