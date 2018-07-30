@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )

        @include('includes.error');
    @else
    @include('includes.adminMenu');

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header"> Details</div>

                        <div class="card-body">
                            {{$quiz->description}}
                            //get first question
                           //show next and previous buttons
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
