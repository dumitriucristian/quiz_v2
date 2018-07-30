@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0)
        @include('includes.error');
    @endif
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
    </div>
@endsection
