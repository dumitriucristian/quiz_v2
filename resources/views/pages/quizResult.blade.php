@extends('layouts.app')

@section('content')
    @if( $errors && count($errors) > 0 )
        @include('includes.error');
    @endif

    {{$result->nr_of_questions}}
@endsection