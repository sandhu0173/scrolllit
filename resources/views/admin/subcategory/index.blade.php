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
                <div class="card-header">{{ __('Subreddits') }}</div>

                <div class="card-body">

                <a href="{{ route('subcat.add',['id'=>$cat_id]) }}" class="btn btn-success">Add New</a>

                @if (Session::has('success'))
                <p class="alert alert-success">{{ Session::get('success') }}</p>
                @endif
                   <table class="table table-bordered datatable">
                   <thead>
                   <tr>
                   <th>#</th>
                   <th>Title</th>
                   <th>Slug</th>
                   <th>Link</th>
                   <th>Action</th>
                   </tr>
                   </thead>
                   <tbody>
                   @foreach($categories as $category)
                   <tr>
                   <td>{{$count++}}</td>
                   <td>{{$category->title }}</th>
                   <td>{{$category->slug }}</th>
                   <td >{{$category->link }}</td>
                   <td>
                   <a href="{{route('subcat.edit',['id'=>$category->id])}}" class="btn btn-sm btn-info">Edit</a>
                   <form action="{{route('subcat.delete',['id'=>$category->id])}}" method="POST">
                   @csrf
                   @method('delete')
                   <input type="hidden" value="{{$category->cat_id}}" name="cat_id">
                   <button class="btn btn-danger btn-sm">Delete</button>
                   </form>
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
