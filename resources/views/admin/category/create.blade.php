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
                <div class="card-header">{{ __('Create Category') }}</div>

                <div class="card-body">
                    <form action="" method="POST"  enctype="multipart/form-data" >
                    @csrf
                    <div class="form-group">
                        <label>Title</label>
                        <input required class="form-control" name="title">
                    </div>
                    <div class="form-group">
      <label for="colorpicker">Button Color</label>
      <input required data-jscolor="{value:'#FF99CC'}" name="color" class="form-control"  />
      
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
    <button class="btn btn-success">Save</button>
    <a href="{{route('categories')}}">Back to list</a>
    </div>
                    </form>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
