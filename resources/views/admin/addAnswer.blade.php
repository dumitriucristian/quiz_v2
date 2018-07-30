@extends('layouts.app')
@section('content')

    @if( $errors && count($errors) > 0)
    @include('includes.error');

    @endif
    <div class="container">
        <div class="row justify-content-center mb-5">

            @include('includes.adminMenu');

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Your question text: {{$question->body}}</div>
                    <div class="card-body">
                        <form method="POST" action="/admin/saveAnswer">
                            {{csrf_field()}}
                            <input type="hidden" name="questionId" value="{{$question->id}}" />
                            <div class="form-group">
                                <label>Add answer:</label>
                                <input type="text" class="form-control" name="body"/>
                            </div>
                            <div class="form-group">
                                <label>Choose answer value</label>
                                <select name="correct" class="form-control">
                                    <option value="">-- Choose a value --</option>
                                    <option value="1" >Correct</option>
                                    <option value="0" >Incrorrect</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm float-right">Add Answer</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>


    @foreach($question->answer as $answer)
            <div class="row justify-content-center mb-1">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="/admin/updateAnswer">
                                {{csrf_field()}}
                                <input type="hidden" name="answerId" value="{{$answer->id}}" />
                                <a href="" class="btn btn-primary btn-sm float-right mb-1"><i class="fas fa-trash"></i></a>
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{$answer->body}}" name="body"/>
                                </div>
                                <div class="form-group">
                                    <label>Choose answer value</label>

                                    <select name="correct" class="form-control">
                                        <option value="{{$answer->selectedValue}}" selected>{{$answer->selectedText}}</option>
                                        <option value="{{$answer->unselectedValue}}" >{{$answer->unselectedText}}</option>

                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm float-right">Update Answer</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
    @endforeach
    </div>

@endsection
