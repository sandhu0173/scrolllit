<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Str;
class SauceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
    
        return view('admin.sauce.index');
    }
    public function getlist(Request $request)
    {
        $post=$request->post;
        $html="";
        $count=1;
        $topposts=DB::table('topposts')->select('topposts.post_title','topposts.id','topposts.sauce','subcategories.link')->join('subcategories','subcategories.id','=','topposts.subcat_id')->where('topposts.post_title','Like','%'.$post.'%')->get();
        foreach($topposts as $post)
        {
            $html.='<tr><td>'.$count++.'</td><td><span class="badge badge-success">Hot</span> '.$post->post_title.'</td><td>'.$post->link.'</td><td><form method="POST" id="top'.$post->id.'" onsubmit="savesauce(event,`top'.$post->id.'`)"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="type" value="top"><input type="hidden" name="post_id" value="'.$post->id.'"> <div class="input-group">  <input type="" class="form-control" placeholder="Enter Sauce Url" value="'.$post->sauce.'"> <div class="input-group-append"> <button class="btn btn-success btn-sm">Update</button> </div></div></form></td></tr>';
        }
        $hotposts=DB::table('hotposts')->select('hotposts.post_title','hotposts.id','hotposts.sauce','subcategories.link')->join('subcategories','subcategories.id','=','hotposts.subcat_id')->where('hotposts.post_title','Like','%'.$post.'%')->get();
        foreach($hotposts as $post)
        {
            $html.='<tr><td>'.$count++.'</td><td><span class="badge badge-danger">Hot</span> '.$post->post_title.'</td><td>'.$post->link.'</td><td><form method="POST" id="hot'.$post->id.'" onsubmit="savesauce(event,`hot'.$post->id.'`)"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="type" value="hot"><input type="hidden" name="post_id" value="'.$post->id.'">
            <div class="input-group">  <input type="" class="form-control" placeholder="Enter Sauce Url" value="'.$post->sauce.'"> <div class="input-group-append"> <button class="btn btn-success btn-sm">Update</button> </div></div></form></td></tr>';
        }
        
        return response()->json(['html'=>$html]);
    }
    function savesauce(Request $request)
    {
            $update=array('sauce'=>$request->sauce);
            if($request->type=="hot")
            {
                DB::table('hotposts')->where('id',$request->post_id)->update($update);
            }
            if($request->type=="top")
            {
                DB::table('topposts')->where('id',$request->post_id)->update($update);
            }
    }
  
}
