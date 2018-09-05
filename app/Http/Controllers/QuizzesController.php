<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use \App\Quiz;
use \App\Question;
use \App\UserQuiz;
use \App\UserAnswerSet;
use \App\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Route;


class QuizzesController extends Controller
{
    public function homePage()
    {

        $quizzes= Quiz::all();

        if( $this->ifQuizExists($quizzes) === false){

            return view('pages.home')->withErrors( ["errors"=>["Are no Quizzess in this moment"]]);
        };

        $this->setHomePageSession();

        return view('pages.home', ["quizzes"=>$quizzes]);
    }


    protected function ifQuizExists( $quizzes )
    {

        if( !$quizzes || $quizzes->count() == 0) {

            return false;
        }

        return true;
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


        $userQuizData = $this->setUserQuizData($quiz_id, $question_id);


        if( UserQuiz::findUserQuiz($userQuizData) == 0){

            $this->setNewUserQuiz($quiz_id);

        }

        if( UserQuiz::findUserQuiz($userQuizData) > 0){

            $this->setUserAnswer($userQuizData, $request) ;

        };


        //check if lastQuestion



        if($request->nextPage != null){
            $isLastQuestion = (new Question)->isLastQuestion($quiz_id, $question_id);

            return redirect( $request->nextPage );
        }


        if($request->nextPage == null)
        {


            //send to results page
        }



        return back();
    }

    protected function setUserAnswer( $userQuizData, $request)
    {

        $answerData = array(
                "question_id" => $request->question_id,
                "user_quiz_id" => UserQuiz::findUserQuiz($userQuizData),
                "user_answer_set" => UserAnswerSet::setUserAnswer($request->answer)
            );


        if(UserAnswerSet::userAnswerSetExist($answerData) == false){

           $this->saveUserAnswer($answerData, $request);
        }

        if(UserAnswerSet::userAnswerSetExist($answerData) == true) {

            $this->updateUserAnswer($answerData, $request);
        }

    }

    protected function setNewUserQuiz($quiz_id)
    {
        $userQuiz = new UserQuiz;
        $userQuiz->quiz_id = $quiz_id;
        $userQuiz->user_id = Auth::User()->id;
        $userQuiz->save();
    }

    protected function setHomePageSession()
    {
        session()->flash('previous-route','home' );

    }

    private function isPreviousPageHomePage()
    {
        return ( session()->has('previous-route') ) ? true : false;
    }

    private function setUserQuizData($quiz_id, $question_id)
    {
        return array(
            'user_id' => Auth::User()->id,
            'quiz_id'=>$quiz_id,
            'question_id'=>$question_id
        );

    }
    private function saveUserAnswer($answerData, $request)
    {
        UserAnswerSet::saveUserAnswerSet($answerData);
        UserAnswer::saveEachUserAnswer($request);
    }

    private function updateUserAnswer($answerData, $request)
    {
        UserAnswerSet::updateUserAnswerSet($answerData);
        UserAnswer::updateEachUserAnswer($request);
    }




    public function quizDetails(Request $request)
    {

       // find user_quiz_id to count how many questions user answered
        $quiz = Quiz::find($request->quiz_id);

        $userQuiz = new  UserQuiz;

        //check if quiz is finished
        $quizIsIncomplete = $userQuiz->quizIsIncomplete( Auth::user()->id, $request->quiz_id );

        if ( $quizIsIncomplete && $this->isPreviousPageHomePage() ) {

          return view('pages.quizSummary', array('quizInfo' =>  $this->getQuizInfo($userQuiz, $request)));

        }

        $questions = Question::where( 'quiz_id', '=', $request->quiz_id )->paginate( 1 );


        if( !$quiz || empty($quiz)){
           return view('pages.quizDetails')->withErrors( array(
               "errors" => ["The quiz requested is unavailable"])
           );
        }

        return view('pages.quizDetails',  array(
                'quiz'=> $quiz,
                'questions' =>  $questions,
                'quizInfo' =>  $this->getQuizInfo($userQuiz, $request)
            )
        );
    }


    protected function getQuizInfo($userQuiz, $request)
    {

        $answerSet = new UserAnswerSet;

        $userQuizId =  $userQuiz->getIncompleteUserQuizId(Auth::user()->id, $request->quiz_id);
        $lastQuestionAnswered = $answerSet->lastQuestionAnsweredId($userQuizId);
        $nrOfAnswers = $answerSet->nrOfQuestionAnswered($userQuizId);
        $nrOfQuestions = (new Question)->nrOfQuestionByQuizId($request->quiz_id);
        $userProgress = (new Quiz)->quizProgress($nrOfQuestions, $nrOfAnswers );

       return  array(
            "quiz_id" => $request->quiz_id,
            "user_id" =>Auth::user()->id,
            "user_quiz_id" => $userQuizId,
            "lastQuestionAnswered" =>$lastQuestionAnswered,
            "nextQuestion" => (new Question)->nextQuestionId($lastQuestionAnswered, $request->quiz_id),
            "nrOfAnswers" => $nrOfAnswers,
            'totalNrOfQuestions'=> $nrOfQuestions,
            'userProgress' => $userProgress
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


    public function resetQuiz(Request $request)
    {
        //delete all user user_answers where user_quiz_id
        //delete all user answer_sets where user_quiz_id
        //redirect to homepage
        dd($request);
        return redirect();
    }

    public function continueQuiz(Request $request)
    {
        //redirect to nextquestion
        return true;
    }

    public function lastQuestion($userQuizId){

        UserQuiz::findOrFail($userQuizId);

        //if there are unanswered question redirect to first question without answer

        //if there are no unanswered question redirect to result page


    }


    public function resultPage($userQuizId)
    {

        //totalNrOfQuestions
        //totalNrOfAnswer
        //totalNrOfCorrectAnswer
        //totalNrOfWrongAnswer
        //totalNrOfPoints
        //totalNrOfPointsObtaine

    }
}
