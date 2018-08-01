@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )

        @include('includes.error');
    @else
         <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">

                    <div class="card">
                        <form method="POST" action="userAnswer">
                        @foreach($questions as $question)
                        <div class="card-header">    {{$question->body}}</div>
                            <div class="card-body">
                                @foreach($question->answer as $answer)
                                    <div>
                                        <input type="checkbox" value="{{$answer->id}}" name="answer[]">
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
    @endif
@endsection
