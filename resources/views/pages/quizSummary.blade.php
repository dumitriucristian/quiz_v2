@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )

        @include('includes.error');
    @endif
         <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="alert alert-primary text-center" role="alert">
                            This quiz is under progress
                        </div>
                        <div class="card-body">
                            <div class="row">


                                <div class="col-md-8 col-sm-12 text-sm-left text-center">
                                    {{$quizInfo['nrOfAnswers']}} answers out of {{$quizInfo['totalNrOfQuestions']}} questions.

                                </div>
                                <div class="col-md-4 col-sm-12 text-center text-md-right">
                                    <span class="float-md-right">50%</span>
                                </div>

                            </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                         style="width: {{$quizInfo['userProgress']}}%" aria-valuenow="{{$quizInfo['userProgress']}}" aria-valuemin="0" aria-valuemax="100">

                                    </div>
                                </div>
                                <div class="p-2">
                                    <p class="text-sm-left text-center">You can choose to continue the quiz or reset everithing.
                                        Be aware if you choose to reset the quiz you will loose all
                                        your answers and the quiz will start from the first
                                        question.
                                    </p>

                                </div>
                            <div class="row">


                               <div class="col-md-6 col-sm-12 p-2 text-center text-md-right">
                                   <a href="/continueQuiz/{{$quizInfo['user_quiz_id']}}" class="btn btn-sm btn-primary">Continue Quiz</a>
                               </div>
                                <div class="col-md-6 col-sm-12 p-2 text-center text-md-left">
                                    <a href="/resetQuiz/{{$quizInfo['user_quiz_id']}}" class="btn btn-sm btn-primary">Reset Quiz</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
