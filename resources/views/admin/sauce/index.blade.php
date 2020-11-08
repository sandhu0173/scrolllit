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
.hide{
    display:none;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
 
            <div class="card">
                <div class="card-header">{{ __('Post Sauce') }}</div>

                <div class="card-body">
                <p class="alert alert-success hide" id="message">Sauce Updated successfully</p>

               <p><input type="text" placeholder="Search a Post" class="form-control" id="search"> 
               </p>
                   <table class="table table-bordered">
                   <thead>
                   <tr>
                   <th>#</th>
                   <th>Title</th>
                   <th>Subreddit</th>
                   <th>Sauce</th>
                   </tr>
                   </thead>
                   <tbody>

                   </tbody>
                   </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
var base_url="{{ url('/') }}";
$('#search').on('keyup',function(e){
    var post=$(this).val();
    if(post.length>2)
    {
        $.ajax({
            url:base_url+"/sauce/list",
            method:'GET',
            data:{post:post},
            dataType:'JSON',
            success:function(result){
                $('tbody').html(result.html);
            }

        });
    }
});
function savesauce(event,formid)
{
    event.preventDefault();
    console.log(formid);
    //console.log($('#'+formid).serialize());
   $.ajax({
            url:base_url+"/sauce/save",
            method:'POST',
            data:$('#'+formid).serialize(),
            success:function(result){
                console.log(result);
                alert('Successfully Updated');
                //$('tbody').html(result.html);
                $('#message').show();
                setTimeout(function(){ $('#message').hide(); }, 3000);
                
            }

        });
}
</script>
@endpush