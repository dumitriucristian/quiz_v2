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
                            @include('includes.progressBar')
                            @if( $quizInfo['userProgress'] == 100)

                                <div class="p-2">
                                    <p class="text-sm-center text-center">
                                        Congrats ! You have answered all the quiz questions.
                                        You can choose to end your quiz and see your results or you can change your answers
                                    </p>

                                </div>
                            @else
                                <div class="p-2">
                                    <p class="text-sm-center text-center">You can choose to continue the quiz or reset everithing.
                                        Be aware if you choose to reset the quiz you will loose all
                                        your answers and the quiz will start from the first
                                        question.
                                    </p>

                                </div>
                            @endif
                            <div class="row">
                                @if( $quizInfo['userProgress'] == 100)
                                    <div class="col-md-6 col-sm-12 p-2 text-center text-md-right">
                                        <a href="{{URL::to('/')}}/resultPage/{{$quizInfo['user_quiz_id']}}" class="btn btn-sm btn-primary">See your quiz results</a>
                                    </div>
                                    <div class="col-md-6 col-sm-12 p-2 text-center text-md-left">
                                        <a href="/quiz/{{$quizInfo['user_quiz_id']}}" class="btn btn-sm btn-primary">Change answers</a>
                                    </div>
                                @else
                                   <div class="col-md-6 col-sm-12 p-2 text-center text-md-right">
                                       <a href="{{URL::to('/')}}/quiz/{{$quizInfo['quiz_id']}}?page={{$quizInfo['nextQuestion']}}&uq={{$quizInfo['user_quiz_id']}}" class="btn btn-sm btn-primary">Continue Quiz</a>
                                   </div>
                                    <div class="col-md-6 col-sm-12 p-2 text-center text-md-left">
                                        <a href="/resetQuiz/{{$quizInfo['quiz_id']}}/{{$quizInfo['user_quiz_id']}}" class="btn btn-sm btn-primary">Reset Quiz</a>
                                   </div>
                               @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
