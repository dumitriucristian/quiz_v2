@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )

        @include('includes.error');
    @endif
    <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">

                    <div class="card-body">
                        This quiz has no questions. Please try later.!
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
