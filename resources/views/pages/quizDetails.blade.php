@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )

        @include('includes.error');
    @endif
         <div class="container">
             <div class="row justify-content-center mb-2">
                 <div class="col-md-8">
                     <div class="card">
                         <div class="card-body">
                             @include('includes.progressBar')
                         </div>
                     </div>
                 </div>
             </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <form method="POST" action="/addUserAnswer">
                            {{csrf_field()}}
                        <input type="hidden" name="user_quiz_id" value="{{$userQuizId}}" />
                        <input type="hidden" name="quiz_id" value="{{$quiz->id}}" />
                        <input type="hidden" name="nextPage" value="{{$questions->nextPageUrl()}}" />
                        @foreach($questions as $question)

                        <div class="card-header">    {{$question->body}}</div>
                            <div class="card-body">
                                <input type="hidden" value="{{$question->id}}" name="question_id">
                                @foreach($question->answer as $answer)
                                    <div>
                                        <input type="hidden"    name="answer[{{$answer->id}}]" value="0">
                                        <input type="checkbox" name="answer[{{$answer->id}}]" value="1" >
                                        <label>{{$answer->body}}</label>
                                    </div>
                                @endforeach
                               <input type="submit" value="submit" />
                            </div>
                        @endforeach()
                        </form>

                       <div class="m-auto">{{$questions->links()}}</div>
                    </div>
                 </div>
            </div>
        </div>

@endsection
