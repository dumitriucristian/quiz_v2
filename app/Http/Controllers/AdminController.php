<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }


    public function addCategory(Request $request)
    {

        $validate =  $request->validate(
            array('name' =>'required|max:255')
        );

        if($validate){
            $category = new \App\Category;
            $category->name = $request->name;

            $category->save();
        }



    }

    public function addTag(Request $request)
    {

        $validate =  $request->validate(
            array('name' =>'required|max:255')
        );

        if($validate){
            $tag = new \App\Tag;
            $tag->name = $request->name;

            $tag->save();
        }



    }


    public function addQuiz()
    {
        return view('admin.addQuiz');
    }

    public function saveQuiz(Request $request)
    {
        $validator = $request->validate([
            'title' =>'required',
            'description'=>'required'
        ]);

        if($validator  === false){

           return back()->withErrors($validator);
        }

        $data = array(
            'title' => $request->title,
            'description' => $request->description
        );

        $quiz  = \App\Quiz::create($data);

        return redirect()->action('AdminController@addQuestion', ['quizId'=>$quiz]);

    }

    public function addQuestion( Request $request)
    {
        $quiz = \App\Quiz::find($request->quizId);

        if(empty($quiz)){
            return  view('admin.addQuestion')->withErrors(["Sorry. Invalid request made "] );
        }

        return view('admin.addQuestion', array('quiz'=>$quiz));
    }

    public function saveQuestion(Request $request)
    {


       $quiz     = \App\Quiz::find($request->quizId);

       if(empty($quiz)){
            return  view('admin.addQuestion')->withErrors(["Sorry. Invalid request made "] );
       }

       $validator = $request->validate([
            'quizId' => 'required|numeric',
            'question'   => 'required'
       ]);

       if($validator === false){
          return view('admin.addQuestion', $quiz )->withErrors($validator);
       }

        $question = \App\Question::create(array("body"=>$request->question));
        $question_quiz_id = \App\QuestionQuiz::create(array("question_id"=>$question->id, "quiz_id" => $request->quizId))->id;
        if($question_quiz_id == 0){
            throw new \Exception("fucked puppup");
        }

        return view('admin.addQuestion',
            array(
                'quiz'=> $quiz
            )
        );
    }

    public function removeQuestion($questionId, $quizId)
    {

        if( empty(\App\Question::find($questionId) )){
            return back()->withErrors(["errors"=>"InvalidRequest"]);
        }

        \App\Question::destroy($questionId);
        $quiz = \App\Quiz::find($quizId);

        return view('admin.addQuestion', array('quiz'=>$quiz));
    }

    public function removeQuiz($quizId)
    {

        \App\Quiz::destroy($quizId);
        $quizzes = \App\Quiz::all();

        return view('admin.quizzes', array("quizzes"=> $quizzes
        ));
    }
    public function updateQuestion(Request $request)
    {
        $quiz = \App\Quiz::find($request->quizId);
        $question = \App\Question::find($request->questionId);
        $question->body = $request->body;
        $question->save();

        return view('admin.addQuestion', array('quiz'=>$quiz));

    }

    public function addAnswer($questionId)
    {

        $question = \App\Question::find($questionId);

        if(empty($question)){
            return view('/quizzes')->withErrors( array("errors" => "invalid request"));
        }

        return view('admin.addAnswer', array('question' => $question));
    }


    public function saveAnswer(Request $request)
    {
       $validator = $request->validate(   array(
                'questionId' => 'required',
                'correct'    => 'required',
                'body'       => 'required'
            )
       );
       if($validator === false){
           return back()->withErrors($validator );
       }

        //save answer data
        $data = array(
            'question_id' => $request->questionId,
            'correct'     => $request->correct,
            'body'       =>  $request->body
        );


        $answer = \App\Answer::create($data);

        $this->saveQuestionValidAnswerSet($request->questionId, $request->correct);

        return back();
    }

    public function saveQuestionValidAnswerSet($questionId, $correct)
    {

        $validAnswerSet = \App\QuestionValidAnswerSet::find($questionId);

        if( empty( $validAnswerSet)){

           $this->saveValidAnswerSet($questionId, $correct);

        }else{

            $newValidAnswer = $validAnswerSet->valid_answer.$correct;

            $validAnswerSet->valid_answer = $newValidAnswer;

            $validAnswerSet->save();
        }

    }


    public function saveValidAnswerSet($questionId, $correct)
    {
        $data = array(
            'question_id' => $questionId,
            'valid_answer' => $correct
        );

       return \App\QuestionValidAnswerSet::create($data);

    }

    public function updateAnswer(Request $request)
    {

        $answer = \App\Answer::find($request->answerId);
        $answer->correct = $request->correct;
        $answer->body    = $request->body;

        $answer->save();

        return back();
    }

    public function quizzes()
    {
       // dd(Auth::user()->roles()->pluck('name'));
       $quizzes =  \App\Quiz::all();

       if( empty($quizzes) ){
           return view('admin.quizzes')->withErrors( ['errors' => "There are no quizzes for the moment"] );
       }

        return view('admin.quizzes', array( 'quizzes' => $quizzes ));
    }

    public function editQuiz($quizId)
    {
        //dd($quizId);
        $quiz =  \App\Quiz::find($quizId);
        $nrOfQuestions = $quiz->questions()->count();


        return view('admin.addQuestion', array('quiz'=>$quiz));
    }




}
