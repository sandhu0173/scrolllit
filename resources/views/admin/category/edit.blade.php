@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.3.3/jscolor.min.js"></script>
<script>
// let's set default options for all color pickers
jscolor.presets.default = { value:'#00CC66', position:'right', backgroundColor:'#333' }
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Edit Category') }}</div>

                <div class="card-body">
                    <form action="" method="POST"  enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Title</label>
                        <input required value="{{$category->title}}" class="form-control" name="title">
                    </div>
                    <div class="form-group">
      <label for="colorpicker">Button Color</label>
      <input required data-jscolor="{value:'{{$category->color}}'}" name="color" class="form-control"  />
      
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
    <button class="btn btn-success">Update</button>
    <a href="{{route('categories')}}">Back to list</a>
    </div>
                    </form>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
