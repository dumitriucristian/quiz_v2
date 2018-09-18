@extends('layouts.app')
@section('content')

    @if( $errors && count($errors) > 0)
        @include('includes.error');

    @else
        @include('includes.adminMenu');

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Your quiz is: {{$quiz->title}}</div>
                        <div class="card-body">
                            {{$quiz->description}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mt-5 mb-5">
                        <div class="card-header">Add quiz question and answers</div>
                        <div class="card-body">
                            <form method="POST" action="/admin/saveQuestion">
                                <div class="form-group">
                                    {{csrf_field()}}
                                    <input type="hidden" name="quizId" value="{{$quiz->id}}">
                                    <label >Add Question</label>
                                    <textarea class="form-control" name="question"></textarea>

                                </div>
                                <button type="submit" class="btn btn-primary float-right">Add Question</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @if($quiz->questions->count() > 0)
               @php  $nr=1; @endphp
                <div class="row justify-content-center">
                    @foreach($quiz->questions as $question)
                    <div class="col-md-12">
                        <div class="card mt-2">
                            <div class="card-body">
                                <form method="POST" action="/admin/updateQuestion">
                                    <div class="form-group">
                                        {{csrf_field()}}
                                        <input type="hidden" name="quizId" value="{{$quiz->id}}">
                                        <input type="hidden" name="questionId" value="{{$question->id}}">
                                        <div class="row pl-3 pr-3 lg-pl-0 lg-pr-0">
                                            <label class="col-md-12 col-lg-8 mr-lg-5 ">Question {{$nr++}}:</label>
                                            <button type="button" id="categoryButton"
                                                    class="btn btn-primary btn-sm float-right position-relative-bottom  col-lg-1 col-md-12  m-1"
                                                    data-toggle="modal" data-question="{{$question->id}}" data-target="#exampleModal">
                                                Category
                                            </button>
                                            <button type="button" id="tagButton"
                                                    class="btn btn-primary btn-sm float-right position-relative-bottom  col-lg-1 col-md-12  m-1"
                                                    data-toggle="modal" data-question="{{$question->id}}" data-target="#exampleModal">
                                                Tags
                                            </button>

                                            <a href="/admin/{{$question->id}}/{{$quiz->id}}/removeQuestion"
                                               class="btn btn-primary float-right position-relative-bottom col-lg-1 col-md-12 m-1">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                        <textarea name="body" class="form-control" value="{{$question->body}}">{{$question->body}}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary float-right">Update  Question</button>
                                    <a href="/admin/{{$question->id}}/addAnswer" class="btn btn-primary float-right mr-2 position-relative-bottom">Answers</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <script src="{{asset('js/tagAdmin.js')}}" defer></script>
@endsection

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


