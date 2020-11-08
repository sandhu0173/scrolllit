@extends('layouts.scroller')

@section('content')
<div class="center-bar">
            <div class="center-center">
                <div>
                    <div class="inline-block center-center">
                    @foreach($categories as $category)
                    
                    @if($count%3==0)
</div>
<div class="inline-block center-center col-md-4">
                    @endif
                        <a href="{{ route('category',['slug'=>$category->slug]) }}">
                            <div class="block">
                                <div class="btn large" style="background-color:{{$category->color}}">
                                    {{$category->title}}
                                </div>
                            </div>
                        </a>
                        <?php 
                   $count++;
                    ?>    
                        @endforeach
 
                    </div>
                    
                </div>
            </div>
        </div>
@endsection
