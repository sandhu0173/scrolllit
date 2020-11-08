@extends('layouts.app')

@section('content')
<style>
.catcolor {
    padding: 5px;
    height: 20px;
    width: 20px;
    border-radius: 20px;
}
.table form {
    display: inline-block;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Categories') }}</div>

                <div class="card-body">

                <a href="{{ route('category.add') }}" class="btn btn-success">Add New</a>

                @if (Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
                @endif
                   <table class="table table-bordered">
                   <thead>
                   <tr>
                   <th>#</th>
                   <th>Title</th>
                   <th>Slug</th>
                   <th>Color</th>
                   <th>Action</th>
                   </tr>
                   </thead>
                   <tbody>
                   @foreach($categories as $category)
                   <tr>
                   <td>{{$count++}}</td>
                   <td>{{$category->title }}</th>
                   <td>{{$category->slug }}</th>
                   <td ><div class="catcolor" style="background-color:{{$category->color }}">&nbsp;</div> </td>
                   <td>
                   <a href="{{route('category.edit',['id'=>$category->id])}}" class="btn btn-sm btn-info">Edit</a>
                   <form action="{{route('category.delete',['id'=>$category->id])}}" method="POST">
                   @csrf
                   @method('delete')
                   <button class="btn btn-danger btn-sm">Delete</button>
                   </form>
                   <a class="btn btn-success btn-sm" href="{{route('sub-categories',['id'=>$category->id])}}">Subreddits</a>
                   </td>
                   </tr>
                   @endforeach
                   </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
