@extends('layouts.app')

@section('content')

    @if( $errors && count($errors) > 0)
        @include('includes.error');
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">


                            You can add a new quiz <a href="/admin/addQuiz">here</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                    @foreach( $quizzes as $quiz)
                        <div class="card-header">
                            <div  class="d-flex justify-content-start">
                               <div>
                                   <a href="/quiz/{{$quiz->id}}">
                                       <strong >{{ $quiz->title }} </strong>
                                   </a>
                               </div>
                                @if($quiz->question)
                                <div>
                                    -  {{$quiz->questions->count()}} questions
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            {{ $quiz->description }}
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
