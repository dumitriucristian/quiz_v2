<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Quiz;
use App\Question;
use App\UserQuiz;
use App\UserAnswerSet;
use App\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\Route;


class QuizzesController extends Controller
{
    protected $userQuiz;
    protected $question;
    protected $quiz;
    protected $userAnswerSet;

    public function __construct(UserQuiz $userQuiz, Question $question, Quiz $quiz, UserAnswerSet $userAnswerSet)
    {

        $this->userQuiz = $userQuiz;
        $this->question = $question;
        $this->quiz     = $quiz;
        $this->userAnswerSet = $userAnswerSet;
    }

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
        $user_quiz_id = $request->user_quiz_id;



        try{

           UserQuiz::findOrFail($user_quiz_id);

        }catch( ModelNotFoundException $Ex){

            return back()->withErrors(array('errors' =>"Invalid Quiz requested"));
        }

        $answers = $request->answer;

        if(in_array(1,$answers) === false ){
            return back()->withErrors(array('errors' =>"Invalid answer. Empty answer"));
        }

        $userQuizData = $this->setUserQuizData($quiz_id, $question_id, $user_quiz_id);

        $this->setUserAnswer($userQuizData, $request) ;

        if($request->nextPage != null){


            return redirect( $request->nextPage.'&uq='.$request->user_quiz_id  );
        }


        if($request->nextPage == null)
        {
            $this->userQuiz->quizIsComplete($user_quiz_id);

        }


         return redirect('addResult/'.$request->user_quiz_id);

    }

    protected function setUserAnswer( $userQuizData, $request)
    {

        $answerData = array(
                "question_id" => $request->question_id,
                "user_quiz_id" => $request->user_quiz_id,
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

        $this->userQuiz->quiz_id = $quiz_id;
        $this->userQuiz->user_id = Auth::User()->id;
        $this->userQuiz->save();
    }

    protected function setHomePageSession()
    {
        session()->flash('previous-route','home' );

    }

    private function isPreviousPageHomePage()
    {
        return ( session()->has('previous-route') ) ? true : false;
    }

    private function setUserQuizData($quiz_id, $question_id, $user_quiz_id)
    {
        return array(
            'user_id' => Auth::User()->id,
            'quiz_id'=>$quiz_id,
            'question_id'=>$question_id,
            'user_quiz_id'=> $user_quiz_id
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

        $quiz = $this->checkQuizExist($request->quiz_id);

        $userData = $this->setUserData($request->quiz_id);

        if( UserQuiz::checkUserQuizExist($userData) == 0){

            $userQuizId =  UserQuiz::init($userData);

        }

        $userQuizId = UserQuiz::findUserQuiz($userData);

        $nrOfUserAnswers = $this->userAnswerSet->nrOfQuestionAnswered($userQuizId);

        $nrOfQuestions  = \App\QuestionQuiz::where( 'quiz_id', '=', $request->quiz_id )->count();

        if($nrOfQuestions == 0){

            return view('pages.noQuestions');
        }

        if($nrOfUserAnswers == 0){

            $questions = Quiz::find($request->quiz_id )->questions()->paginate( 1 );


            return view('pages.quizDetails',  array(

                    'quiz'=> $quiz,
                    'questions' =>  $questions,
                    'userQuizId' => $userQuizId,
                    'quizInfo' =>  $this->getQuizInfoDefault( $request, $userQuizId )
                )
            );
        }

        //check if quiz is finished
        $quizIsIncomplete = $this->userQuiz->quizIsIncomplete(  $userQuizId );

        if ( $quizIsIncomplete && $this->isPreviousPageHomePage() ) {

            $request->uq = $userQuizId;

          return view('pages.quizSummary', array('quizInfo' =>  array()));

        }


        $questions = $this->quiz::find( $request->quiz_id )->questions()->paginate( 1 );

       $this->checkUserQuizIdIsValid($userQuizId);
       $request->uq = $userQuizId;
       $quizInfo  = $this->getQuizInfo( $request );

        return view('pages.quizDetails',  array(
                        'quiz'=> $quiz,
                        'questions' =>  $questions,
                        'userQuizId'=> $userQuizId,
                        'quizInfo' => $quizInfo
                    ));
    }


    protected function setUserData( $quizId){

        return  array("user_id" => Auth::user()->id, "quiz_id" => $quizId);
    }


    protected function checkUserQuizIdIsValid($userQuizId)
    {
        return ( UserQuiz::findOrFail($userQuizId)->where('user_id','=', Auth::user()->id)->count() > 0) ? true :false ;
    }

    protected function getQuizInfo( $request)
    {

        $nrOfAnswers = $this->userAnswerSet->nrOfQuestionAnswered($request->quiz_id);
        $nrOfQuestions = $this->quiz->find($request->quiz_id)->questions()->count();
        $lastQuestionAnswered = (new UserAnswerSet)->lastQuestionAnswered(  $request->uq);
        $nextQuestion = $this->question->nextQuestionId($lastQuestionAnswered, $request->quiz_id);
        $userProgress = $this->quiz->quizProgress($nrOfQuestions, $nrOfAnswers );

        return array(
           "quiz_id" => $request->quiz_id,
           "user_id" => Auth::user()->id,
           "user_quiz_id" => $request->uq,
            "lastQuestionAnswered" =>$lastQuestionAnswered,
            "nextQuestion" => $nextQuestion,
           "nrOfAnswers" =>  $nrOfAnswers,
           "totalNrOfQuestions" => $nrOfQuestions,
           "userProgress"   => $userProgress,
           "nrOfQuestions" => $nrOfQuestions,
        );

    }

    protected function checkQuizExist($quiz_id)
    {
        return Quiz::findOrFail($quiz_id);
    }

    protected function getQuizInfoDefault( $request, $userQuizId)
    {

        $nrOfAnswers = 0;
        $nrOfQuestions = $this->quiz->find($request->quiz_id)->count();

        $userProgress = $this->quiz->quizProgress($nrOfQuestions, $nrOfAnswers );

        return  array(
            'user_quiz_id'=>$userQuizId,
            "quiz_id" => $request->quiz_id,
            "user_id" => Auth::user()->id,
            "user_quiz_id" => $userQuizId,
            "lastQuestionAnswered" => 0,
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


    public function resetQuiz($quizId, $userQuizId)
    {

       UserQuiz::destroy($userQuizId);
       UserAnswerSet::where('user_quiz_id',$userQuizId)->delete();
       UserAnswer::where('user_quiz_id', $userQuizId)->delete();


        return redirect('/quiz/'.$quizId);
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
        dd($userQuizId);
        //totalNrOfQuestions
        //totalNrOfAnswer
        //totalNrOfCorrectAnswer
        //totalNrOfWrongAnswer
        //totalNrOfPoints
        //totalNrOfPointsObtaine

    }
}
