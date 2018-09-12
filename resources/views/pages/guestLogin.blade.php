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
                        <p>In this moment you are not authenticated.</p>
                        <p>Please <a href="{{url('/login')}}">login</a>, or <a href="{{url('/register')}}">create a new account</a>
                            to use all great futures like:</p>
                        <ul>
                            <li>Quiz results history</li>
                            <li>Quiz evolution graph</li>
                            <li>And many others</li>
                        </ul>

                        <p>You can even use a <a href="{{url('/loginAsGuest')}}">guest account</a>, but be aware
                            the quest account won't provide you access to all the futures we have.
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
