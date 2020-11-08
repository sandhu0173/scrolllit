@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Edit Subreddit') }}</div>

                <div class="card-body">
                    <form action="" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="cat_id" value="{{$category->cat_id}}">
                    <div class="form-group">
                        <label>Title</label>
                        <input required value="{{$category->title}}" class="form-control" name="title">
                    </div>
                    <div class="form-group">
                        <label for="colorpicker">Link</label>
                        <input required placeholder="Example. r/Sextrophies" name="link" class="form-control" value="{{$category->link}}"  />
                        
                    </div>
                    <div class="form-group">
                        <label>Profile</label>
                        @if($category->profile!="")
                        <img src="{{ asset($category->profile)}}" width="50">
                        @endif
                        <input class="form-control" type="file" name="profile">
                    </div>
                    <div class="form-group">

                        <label>Cover</label>
                        @if($category->cover!="")
                        <img src="{{ asset($category->cover)}}" width="50">
                        @endif
                        <input type="file" class="form-control" name="cover">
                    </div>
                    <div class="form-group">
                        <label>Tags</label>
                        <input type="" value="{{$category->tags}}" class="form-control" name="tags">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description">{{$category->description}}</textarea>
                    </div>

    <div class="form-group">
    <button class="btn btn-success">Update</button>
    <a href="{{route('sub-categories',['id'=>$category->cat_id])}}">Back to list</a>
    </div>
                    </form>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
