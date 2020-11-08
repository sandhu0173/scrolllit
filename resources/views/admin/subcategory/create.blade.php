@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Add Subreddit') }}</div>

                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <input type="hidden" name="cat_id" value="{{$cat_id}}">
                    <div class="form-group">
                        <label>Title</label>
                        <input required class="form-control" name="title">
                    </div>
                    
                    <div class="form-group">
                        <label for="colorpicker">Link</label>
                        <input required placeholder="Example. r/Sextrophies" type="text" name="link" class="form-control"  />
                    </div>
                    <div class="form-group">
                        <label>Profile</label>
                        <input class="form-control" type="file" name="profile">
                    </div>
                    <div class="form-group">
                        <label>Cover</label>
                        <input type="file" class="form-control" name="cover">
                    </div>
                    <div class="form-group">
                        <label>Tags</label>
                        <input type="" class="form-control" name="tags">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>

    <div class="form-group">
    <button class="btn btn-success">Save</button>
    <a href="{{route('sub-categories',['id'=>$cat_id])}}">Back to list</a>
    </div>
                    </form>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
