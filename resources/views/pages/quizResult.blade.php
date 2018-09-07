@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )
        @include('includes.error');
    @endif

    <div class="container">
        <div class="row justify-content-center mb-2">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Quiz summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success text-center font-weight-bold text-uppercase" role="alert">
                            Your score is:  {{$result->successRatio}}
                        </div>

                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Number of questions:
                                <span class="badge badge-primary ">{{$result->nr_of_questions}}</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Number of answers:
                                <span class="badge badge-primary "> {{$result->nr_of_questions_answered}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Number of correct answers:
                                <span class="badge badge-primary ">{{$result->nr_of_correct_answers}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Number of incorrect answers:
                                <span class="badge badge-primary ">{{$result->nr_of_incorrect_answers}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .badge{
        font-size: 1.2em !important;
        font-weight: bold !important;
    }
</style>