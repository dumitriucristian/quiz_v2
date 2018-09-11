@extends('layouts.app')
@section('content')
    @if( $errors && count($errors) > 0)
        @include('includes.error');
    @elseif($quizzes)
        @include('includes.adminMenu');

        <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form method="POST" action="/admin/saveQuiz">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Quiz Title:</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Quiz Description:</label>
                    </div>

                    <div class="form-group">
                        <textarea name="description" class="form-control" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Quiz</button>
                </form>
            </div>
        </div>
        <span class="card mt-5">
             @foreach($quizzes as $quiz)

                <span class="card-header">
                    <div class="row">
                         <div class="col-md-11 ">
                            <span class="badge badge-pill badge-primary p-2 mr-2">  </span>
                             {{$quiz->title}}
                         </div>
                         <a href="/admin/{{$quiz->id}}/removeQuiz/" class="btn-primary btn-sm float-right mr-3"><i class="fas fa-trash"></i></a>
                    </div>
                </span>
                <div class="card-body">
                 <div class="row">
                     <div class="col-md-11 d-flex align-items-center">
                        {{$quiz->description}}

                    </div>
                    <div class="d-flex flex-column">
                        <a href="/quiz/{{$quiz->id}}" class="btn-primary btn-sm mb-1"><i class="far fa-eye"></i></a>
                        <a href="/admin/{{$quiz->id}}/editQuiz/" class="btn-primary btn-sm mb-1"><i class="fas fa-pencil-alt"></i></a>

                    </div>
                </div>
             </div>
            @endforeach
        </div>
    </div>
    @endif
    </div>
@endsection

