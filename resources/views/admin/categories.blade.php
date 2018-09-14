@extends('layouts.app')
@section('content')
    @if( $errors && count($errors) > 0)
        @include('includes.error');
    @elseif($categories)
        @include('includes.adminMenu');

        <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <form method="POST" action="/admin/addCategory">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Category name:</label>
                        <input type="text" name="name" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
                <form method="POST" action="/admin/addTag">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label>Category name:</label>
                        <input type="text" name="name" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary">Add Tag</button>
                </form>
            </div>
        </div>
        <span class="card mt-5">
             @foreach($categories as $category)

                <span class="card-header">
                    <div class="row">
                         <div class="col-md-11 ">
                            <span class="badge badge-pill badge-primary p-2 mr-2">  </span>
                             {{$category->name}}
                         </div>
                         <a href="/admin/{{$category->id}}/removeCategory/" class="btn-primary btn-sm float-right mr-3"><i class="fas fa-trash"></i></a>
                    </div>
                </span>
                <div class="card-body">
                 <div class="row">
                     <div class="col-md-11 d-flex align-items-center">
                        {{$category->description}}

                    </div>
                    <div class="d-flex flex-column">
                        <a href="/quiz/{{$category->id}}" class="btn-primary btn-sm mb-1"><i class="far fa-eye"></i></a>
                        <a href="/admin/{{$category->id}}/editCategory/" class="btn-primary btn-sm mb-1"><i class="fas fa-pencil-alt"></i></a>

                    </div>
                </div>
             </div>
            @endforeach
        </div>
    </div>
    @endif
    </div>
@endsection

