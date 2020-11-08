<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class HomeController extends Controller
{
    public function index(Request $request )
    {
        $data['categories']=DB::table('categories')->get();
        $data['count']=0;
        return view('home',$data);
    }
    function category($slug)
    {
        return view('subreddit'); 
    }
    function subreddit($slug)
    {
        return view('subreddit');
    }

}
