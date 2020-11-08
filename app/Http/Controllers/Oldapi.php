<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use File;
use Illuminate\Support\Facades\Storage;

class Api extends Controller
{
    private $limit=10;
    private $catlimit=2;
    function categories(Request $request,$offset)
    {
        $limit=3;
       $categories=DB::table('categories')->select('subcategories.slug','subcategories.title')->join('subcategories','categories.id','=','subcategories.cat_id')->offset($offset)->limit($limit)->get();
       $next=$offset+$limit;
       return response()->json(['categories' => $categories,'status'=>true,'offset'=>$next]);
    }
    
    function adultcategories(Request $request,$offset)
    {
        $limit=3;
        
       $categories=DB::table('categories')->select('subcategories.slug','subcategories.title')->join('subcategories','categories.id','=','subcategories.cat_id')->where('subcategories.cat_id','7')->offset($offset)->limit($limit)->get();
       $next=$offset+$limit;
       return response()->json(['categories' => $categories,'offset'=>$next,'status'=>true]);
    }
    function catdetail(Request $request,$slug )
    {
       $category=DB::table('categories')->where('slug',$slug)->first();
       $subcategories=DB::table('subcategories')->where('cat_id',$category->id)->get();
       return response()->json(['category' => $category,'subcategories'=>$subcategories,'status'=>true]);
    }
    function subcatdetail(Request $request,$slug )
    {
       $category=DB::table('subcategories')->where('slug',$slug)->first();
       $subcategories=DB::table('subcategories')->where('cat_id',$category->cat_id)->where('id','!=',$category->id)->get();
       return response()->json(['category' => $category,'subcategories'=>$subcategories,'status'=>true]);
    }
    function postdetail(Request $request,$slug )
    {
       $hot=DB::table('hotposts')->where('slug',$slug)->first();
       if($hot)
       {
           $id=$hot->id-1;
           $cat_id=$hot->subcat_id;
           $slug=$hot->slug;
           $previous=DB::table('hotposts')->where('subcat_id',$cat_id)->where('id',"<",$id)->where('slug','!=',$slug)->first();
           $next=DB::table('hotposts')->where('subcat_id',$cat_id)->where('id',">",$id)->where('slug','!=',$slug)->first();
           $posts[]= $previous;
           $posts[]= $hot;
           $posts[]= $next;
           
            return response()->json(['posts' => $posts,'type'=>'hot','status'=>true]);
       }else{
            $top=DB::table('topposts')->where('slug',$slug)->first();
            $id=$top->id;
            $slug=$top->slug;
           $cat_id=$top->subcat_id;
           $previous=DB::table('topposts')->where('subcat_id',$cat_id)->where('slug','!=',$slug)->where('id',"<",$id)->first();
           $next=DB::table('topposts')->where('subcat_id',$cat_id)->where('slug','!=',$slug)->where('id',">",$id)->first();
           $posts[]= $previous;
           $posts[]= $top;
           $posts[]= $next;
           
            return response()->json(['posts' => $posts,'type'=>'top','status'=>true]);
       }
      
    }
    function savehotdata($id)
    {
        $subcat=DB::table('subcategories')->where('id',$id)->first();

        $url='https://www.reddit.com/'.trim($subcat->link,"/").'/hot/.json';
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($cURLConnection);
        
        curl_close($cURLConnection);

        $data=json_decode($response);
        foreach($data->data->children as $row)
        {
            $subcat_id=$subcat->id;
            if(isset($row->data->post_hint))
            {
                $post_type=$row->data->post_hint;
                if(($post_type=="rich:video") || ($post_type=="image"))
                {
                $post_id=$row->data->id;
                $check=DB::table('hotposts')->where('post_id',$post_id)->count();
                if($check==0)
                {
                $subreddit=$row->data->subreddit_name_prefixed;
                $post_title=$row->data->title;
                $likes=$row->data->ups;
                $unlikes=$row->data->downs;
                $comments=$row->data->num_comments;
                $url="";
                $status="1";
                //new
                
                $subreddit_id=$row->data->subreddit_id;
                $permalink=$row->data->permalink;
                $created_at=date('Y-m-d H:i:s');
                $updated_at=date('Y-m-d H:i:s');
                $thumbnail="";
                if(isset($row->data->thumbnail))
                {
                    if(isset($row->data->preview->images[0]->source->url))
                    {
                        $thumb=$row->data->preview->images[0]->source->url;
                    }else{
                        $thumb=$row->data->thumbnail;
                    }
                    
                    
                    if ((strpos($thumb, ".jpg")) || (strpos($thumb, ".png"))) {
                        // Storage::download($url);
                        $url_components = parse_url($thumb);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }
                        $path = public_path('uploads/thumbnail/'.$str);

                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }else{
                            file_put_contents("uploads/thumbnail".$url_components['path'],file_get_contents(str_replace("amp;","",$thumb)) );
                            $thumbnail="uploads/thumbnail".$url_components['path'];
                        }    
                    }else{
                        $thumbnail=$thumb;
                    
                    }
                }
                if($post_type=="image")
                {
                    if(isset($row->data->url))
                    {
                        $imagelink=$row->data->url;
                        // Storage::download($url);
                        $url_components = parse_url($imagelink);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }       
                        $path = public_path('uploads/images/'.$str);
   
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        file_put_contents("uploads/images".$url_components['path'], file_get_contents($imagelink));
                        $url="uploads/images".$url_components['path'];
                    }

                }
                if($post_type=="rich:video")
                {
                    
                    if(isset($row->data->preview->reddit_video_preview))
                    {
                        
                        $videolink=$row->data->preview->reddit_video_preview->fallback_url;;
                        // Storage::download($url);
                        $url_components = parse_url($videolink);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }
                      $path = public_path('uploads/gifs/'.$str);
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        file_put_contents("uploads/gifs".$url_components['path'], file_get_contents($videolink));

                        $url="uploads/gifs".$url_components['path'];
                    }
                }
            
                $insert=array('subcat_id'=>$subcat_id,
                          'post_id'=>$post_id,
                          'likes'=>$likes,
                          'unlikes'=>$unlikes,
                          'comments'=>$comments,
                          'post_type'=>$post_type,
                          'post_title'=>$post_title,
                          'slug'=>Str::slug($post_title),
                          'url'=>$url,
                          'subreddit'=>$subreddit,
                          'thumbnail'=>$thumbnail,
                          'subreddit_id'=>$subreddit_id,
                          'permalink'=>$permalink,
                          'status'=>$status,
                          'created_at'=>$created_at,
                          'updated_at'=>$updated_at,
                );
                
                    DB::table('hotposts')->insert($insert);
                }
            }
        }
        }
        
        
    }
    function savetopdata($id)
    {
        $subcat=DB::table('subcategories')->where('id',$id)->first();
        $url='https://www.reddit.com/'.trim($subcat->link,"/").'/top/.json';
        
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($cURLConnection);
        
        curl_close($cURLConnection);

        $data=json_decode($response);
        foreach($data->data->children as $row)
        {
            $subcat_id=$subcat->id;
            if(isset($row->data->post_hint))
            {
                $post_type=$row->data->post_hint;
                if(($post_type=="rich:video") || ($post_type=="image"))
                {
                $post_id=$row->data->id;
                $check=DB::table('topposts')->where('post_id',$post_id)->count();
                if($check==0)
                {
                $subreddit=$row->data->subreddit_name_prefixed;
                $post_title=$row->data->title;
                $likes=$row->data->ups;
                $unlikes=$row->data->downs;
                $comments=$row->data->num_comments;
                $url="";
                $status="1";
                //new
                
                $subreddit_id=$row->data->subreddit_id;
                $permalink=$row->data->permalink;
                $created_at=date('Y-m-d H:i:s');
                $updated_at=date('Y-m-d H:i:s');
                $thumbnail="";
                if(isset($row->data->thumbnail))
                {
                    if(isset($row->data->preview->images[0]->source->url))
                    {
                        $thumb=$row->data->preview->images[0]->source->url;
                    }else{
                        $thumb=$row->data->thumbnail;
                    }
                    
                    
                    if ((strpos($thumb, ".jpg")) || (strpos($thumb, ".png"))) {
                        // Storage::download($url);
                        $url_components = parse_url($thumb);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }
                        $path = public_path('uploads/thumbnail/'.$str);

                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }else{
                         file_put_contents("uploads/thumbnail".$url_components['path'],file_get_contents(str_replace("amp;","",$thumb)) );
                            $thumbnail="uploads/thumbnail".$url_components['path'];
                        }    
                    }else{
                        $thumbnail=$thumb;
                    
                    }
                }
                if($post_type=="image")
                {
                    if(isset($row->data->url))
                    {
                        $imagelink=$row->data->url;
                        // Storage::download($url);
                        $url_components = parse_url($imagelink);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }       
                        $path = public_path('uploads/images/'.$str);
   
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        file_put_contents("uploads/images".$url_components['path'], file_get_contents($imagelink));
                        $url="uploads/images".$url_components['path'];
                    }

                }
                if($post_type=="rich:video")
                {
                    
                    if(isset($row->data->preview->reddit_video_preview))
                    {
                        
                        $videolink=$row->data->preview->reddit_video_preview->fallback_url;;
                        // Storage::download($url);
                        $url_components = parse_url($videolink);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }
                      $path = public_path('uploads/gifs/'.$str);
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        file_put_contents("uploads/gifs".$url_components['path'], file_get_contents($videolink));

                        $url="uploads/gifs".$url_components['path'];
                    }
                }
            
                $insert=array('subcat_id'=>$subcat_id,
                          'post_id'=>$post_id,
                          'likes'=>$likes,
                          'unlikes'=>$unlikes,
                          'comments'=>$comments,
                          'post_type'=>$post_type,
                          'post_title'=>$post_title,
                          'slug'=>Str::slug($post_title),
                          'url'=>$url,
                          'subreddit'=>$subreddit,
                          'thumbnail'=>$thumbnail,
                          'subreddit_id'=>$subreddit_id,
                          'permalink'=>$permalink,
                          'status'=>$status,
                          'created_at'=>$created_at,
                          'updated_at'=>$updated_at,
                );
                
                    DB::table('topposts')->insert($insert);
                }
            }
            }            

        }
        
    }
    function getthumb($url)
    {
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, $url);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($cURLConnection);
        
        curl_close($cURLConnection);
        
        $data=json_decode($response);
        return $data;
    }
    function getdata(Request $request)
    {
        $cURLConnection = curl_init();

        //curl_setopt($cURLConnection, CURLOPT_URL, 'https://www.reddit.com/r/FoodPorn/top/.json');
        curl_setopt($cURLConnection, CURLOPT_URL, 'https://www.reddit.com/r/natureismetal/top/.json');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($cURLConnection);
        
        curl_close($cURLConnection);
        
        $data=json_decode($response);
        
        foreach($data->data->children as $row)
        {
            $subcat_id='50';
            if(isset($row->data->post_hint))
            {
                echo "<pre>";
                print_r($row);
                exit;
                $post_type=$row->data->post_hint;
                if(($post_type=="rich:video") || ($post_type=="image"))
                {
                $post_id=$row->data->id;
                $subreddit=$row->data->subreddit_name_prefixed;
                $post_title=$row->data->title;
                $likes=$row->data->ups;
                $unlikes=$row->data->downs;
                $comments=$row->data->num_comments;
                $url="";
                $status="1";
                //new
                
                $subreddit_id=$row->data->subreddit_id;
                $permalink=$row->data->permalink;
                $created_at=date('Y-m-d H:i:s');
                $updated_at=date('Y-m-d H:i:s');
                $thumbnail="";
                if(isset($row->data->thumbnail))
                {
                    if(isset($row->data->preview->images[0]->source->url))
                    {
                        $thumb=$row->data->preview->images[0]->source->url;
                    }else{
                        $thumb=$row->data->thumbnail;
                    }
                    if ((strpos($thumb, ".jpg")) || (strpos($thumb, ".png"))) {
                        // Storage::download($url);
                        $url_components = parse_url($thumb);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }
                        $path = public_path('uploads/thumbnail/'.$str);

                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                            file_put_contents("uploads/thumbnail".$url_components['path'],file_get_contents(str_replace("amp;","",$thumb)) );
                        $thumbnail="uploads/thumbnail".$url_components['path'];
                    }else{
                        $thumbnail=$thumb;
                    
                    }
                }
                if($post_type=="image")
                {
                    if(isset($row->data->url))
                    {
                        $imagelink=$row->data->url;
                        // Storage::download($url);
                        $url_components = parse_url($imagelink);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }       
                        $path = public_path('uploads/images/'.$str);
   
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        file_put_contents("uploads/images".$url_components['path'], file_get_contents($imagelink));
                        $url="uploads/images".$url_components['path'];
                    }

                }
                if($post_type=="rich:video")
                {
                    
                    if(isset($row->data->preview->reddit_video_preview))
                    {
                        
                        $videolink=$row->data->preview->reddit_video_preview->fallback_url;;
                        // Storage::download($url);
                        $url_components = parse_url($videolink);
                        $arr=explode("/",$url_components['path']);
                        //create sub folder in folder 
                        $to=count($arr)-1;
                        $str="";
                        for($i=0;$i<$to;$i++)
                        {
                            $str.=$arr[$i]."/";
                        }
                      $path = public_path('uploads/gifs/'.$str);
                        if(!File::isDirectory($path)){
                            File::makeDirectory($path, 0777, true, true);
                        }
                        file_put_contents("uploads/gifs".$url_components['path'], file_get_contents($videolink));

                        $url="uploads/gifs".$url_components['path'];
                    }
                }
            }
            
                $insert=array('subcat_id'=>$subcat_id,
                          'post_id'=>$post_id,
                          'likes'=>$likes,
                          'unlikes'=>$unlikes,
                          'comments'=>$comments,
                          'post_type'=>$post_type,
                          'post_title'=>$post_title,
                          'slug'=>Str::slug($post_title),
                          'url'=>$url,
                          'subreddit'=>$subreddit,
                          'thumbnail'=>$thumbnail,
                          'subreddit_id'=>$subreddit_id,
                          'permalink'=>$permalink,
                          'status'=>$status,
                          'created_at'=>$created_at,
                          'updated_at'=>$updated_at,
                );
                print_r($insert);
                echo "<br><br>";
                /*$check=DB::table('topposts')->where('post_id',$post_id)->count();
                if($check==0)
                {
                DB::table('topposts')->insert($insert);
                }*/
            }
            

        }
    }
    function topdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        //$posts=$q->limit($this->limit)->orderBy('id',"DESC")->get();
        $fo=0;
        $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
        //next offset
        $so=$fo+$limit;
        $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
        //third offset
        $to=$so+$limit;
        $third=$q->offset($to)->limit($limit)->orderBy('id',"DESC")->get();
        $lo=$to+$limit;
        return response()->json(['first' => $first,'second'=>$second,'third'=>$third,'status'=>true,'type'=>'top','offset'=>$lo]);
    }
    function hotdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        $this->savehotdata($sub->id);
        $q=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        $fo=0;
        $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
        //next offset
        $so=$fo+$limit;
        $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
        //third offset
        $to=$so+$limit;
        $third=$q->offset($to)->limit($limit)->orderBy('id',"DESC")->get();
        $lo=$to+$limit;
        return response()->json(['first' => $first,'second'=>$second,'third'=>$third,'status'=>true,'type'=>'hot','offset'=>$lo]);
    }
    /*
    @get more data of a list
    */
    function moretopdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $next=$request->offset+$limit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
      //  $posts=$q->limit($this->limit)->orderBy('id',"DESC")->get();
              $fo=$next;
              $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
              //next offset
              $so=$fo+$limit;
              $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
              //third offset
              $to=$so+$limit;
              $third=$q->offset($to)->limit($limit)->orderBy('id',"DESC")->get();
              $lo=$to+$limit;
              return response()->json(['first' => $first,'second'=>$second,'third'=>$third,'status'=>true,'type'=>'top','offset'=>$lo]);
        //return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next]);
    }
    /*
    @get more data in from the hot post
    */ 
    function morehotdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $next=$request->offset+$limit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savehotdata($sub->id);
        $q=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        $fo=$next;
              $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
              //next offset
              $so=$fo+$limit;
              $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
              //third offset
              $to=$so+$limit;
              $third=$q->offset($to)->limit($limit)->orderBy('id',"DESC")->get();
              $lo=$to+$limit;
              return response()->json(['first' => $first,'second'=>$second,'third'=>$third,'status'=>true,'type'=>'hot','offset'=>$lo]);
        /*$posts=$q->limit($this->limit)->orderBy('id',"DESC")->get();
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$next]);*/
    }





    function mobiletopdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        //$posts=$q->limit($this->limit)->orderBy('id',"DESC")->get();
        $fo=0;
        $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
        //next offset
        $so=$fo+$limit;
        $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
        //third offset
        $to=$so+$limit;
        return response()->json(['first' => $first,'second'=>$second,'status'=>true,'type'=>'top','offset'=>$to]);
    }
    function mobilehotdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        $this->savehotdata($sub->id);
        $q=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        $fo=0;
        $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
        //next offset
        $so=$fo+$limit;
        $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
        //third offset
        $to=$so+$limit;
        return response()->json(['first' => $first,'second'=>$second,'status'=>true,'type'=>'hot','offset'=>$to]);
    }
    /*
    @get more data of a list
    */
    function mobilemoretopdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $next=$request->offset+$limit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
      //  $posts=$q->limit($this->limit)->orderBy('id',"DESC")->get();
              $fo=$next;
              $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
              //next offset
              $so=$fo+$limit;
              $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
              //third offset
              $to=$so+$limit;
              return response()->json(['first' => $first,'second'=>$second,'status'=>true,'type'=>'top','offset'=>$to]);
        //return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next]);
    }
    /*
    @get more data in from the hot post
    */ 
    function mobilemorehotdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $next=$request->offset+$limit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savehotdata($sub->id);
        $q=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        $fo=$next;
              $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
              //next offset
              $so=$fo+$limit;
              $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
              //third offset
              $to=$so+$limit;
              
              return response()->json(['first' => $first,'second'=>$second,'status'=>true,'type'=>'hot','offset'=>$to]);
        /*$posts=$q->limit($this->limit)->orderBy('id',"DESC")->get();
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$next]);*/
    }
    
    function cattop(Request $request,$slug)
    {
        $cat=DB::table('categories')->where('slug',$slug)->first();
        
        $subs=DB::table('subcategories')->where('cat_id',$cat->id)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
            //$this->savetopdata($sub->id);
            $sub_arr[]=$sub->id;
        }
        
        $posts=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->limit($this->limit)->orderBy('id',"DESC")->get();
        
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$this->limit]);
    }
    function cathot(Request $request,$slug)
    {
        $cat=DB::table('categories')->where('slug',$slug)->first();
        $subs=DB::table('subcategories')->where('cat_id',$cat->id)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
            //$this->savehotdata($sub->id);
            $sub_arr[]=$sub->id;
        }
        $posts=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub_arr)->limit($this->limit)->orderBy('id',"DESC")->get();

        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$this->limit]);
    }
        /*
    @load more on scroll
    */
    function morecattop(Request $request,$slug,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        
        $cat=DB::table('categories')->where('slug',$slug)->first();
        
        $subs=DB::table('subcategories')->where('cat_id',$cat->id)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
            //$this->savetopdata($sub->id);
            $sub_arr[]=$sub->id;
        }
        
        $posts=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
        
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next]);
    }
    /*
    @get records from subreddit
    */
    function moresubcattop(Request $request,$slug,$filter,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        
            $subs=DB::table('subcategories')->where('slug',$slug)->get();
        
        $sub_arr=[];
        foreach($subs as $sub)
        {
            //$this->savetopdata($sub->id);
            $sub_arr[]=$sub->id;
        }
        if($filter=='picture')
            {
                //get only image content
                $type="image";
                $posts=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->where('post_type',$type)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
            }
            elseif($filter=='videos')
            {
                //get only video content
                $type="rich:video";
                $posts=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->where('post_type',$type)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
            }else{
                //get all adult content
                $posts=DB::table('topposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
            }
        
        
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next,'limit'=>$limit]);
    }
    /*
    @load more on scroll
    */
    function morecathot(Request $request,$slug,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        
        $cat=DB::table('categories')->where('slug',$slug)->first();
        $subs=DB::table('subcategories')->where('cat_id',$cat->id)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
           // $this->savehotdata($sub->id);
            $sub_arr[]=$sub->id;
        }
        $posts=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub_arr)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();

        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$next]);
    }
        /*
    @load subreddit data
    */
    function moresubcathot(Request $request,$slug,$filter,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        
            $subs=DB::table('subcategories')->where('slug',$slug)->get();
        
        $sub_arr=[];
        foreach($subs as $sub)
        {
            //$this->savehotdata($sub->id);
            $sub_arr[]=$sub->id;
        }
        if($filter=='picture')
            {
                //get only image content
                $type="image";
                $posts=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->where('post_type',$type)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
            }
            elseif($filter=='videos')
            {
                //get only video content
                $type="rich:video";
                $posts=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->where('post_type',$type)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
            }else{
                //get all adult content
                $posts=DB::table('hotposts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
            }
        
        
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$next,'limit'=>$limit]);
    }

    function checktoplike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('topposts_likes')->where('post_id',$postid)->where('userid',$user_id)->count();
        $likes=DB::table('topposts_likes')->where('post_id',$postid)->count();
        if($check>0)
        {
            return response()->json(['status'=>true,'likes'=>$likes]);
        }else{
            return response()->json(['status'=>false,'likes'=>$likes]);
        }
        
    }
    function toppostlike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('topposts_likes')->where('post_id',$postid)->where('userid',$user_id)->count();
        if($check>0)
        {
            $post=DB::table('topposts_likes')->select('id')->where('post_id',$postid)->where('userid',$user_id)->first();
            DB::table('topposts_likes')->where('id',$post->id)->delete();
            $likes=$check-1;
            return response()->json(['status'=>false,'likes'=>$likes]);
        }else{
            $insert=array('post_id'=>$postid,
                            'userip'=>$ip,
                            'userid'=>$user_id,
                            'status'=>'1',
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                        );
                        DB::table('topposts_likes')->insert($insert);
                        $likes=$check+1;
            return response()->json(['status'=>true,'likes'=>$likes]);
        }

    }
    function checkhotlike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('hotposts_likes')->where('post_id',$postid)->where('userid',$user_id)->count();
        $likes=DB::table('hotposts_likes')->where('post_id',$postid)->count();
        if($check>0)
        {
            return response()->json(['status'=>true,'likes'=>$likes]);
        }else{
            return response()->json(['status'=>false,'likes'=>$likes]);
        }
        
    }
    function hotpostlike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('hotposts_likes')->where('post_id',$postid)->where('userid',$user_id)->count();
        if($check>0)
        {
            $post=DB::table('hotposts_likes')->select('id')->where('post_id',$postid)->where('userid',$user_id)->first();
            DB::table('hotposts_likes')->where('id',$post->id)->delete();
            $likes=$check-1;
            return response()->json(['status'=>false,'likes'=>$likes]);
        }else{
            $insert=array('post_id'=>$postid,
                            'userip'=>$ip,
                            'userid'=>$user_id,
                            'status'=>'1',
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                        );
                        DB::table('hotposts_likes')->insert($insert);
                        $likes=$check+1;
            return response()->json(['status'=>true,'likes'=>$likes]);
        }


    }
    function search(Request $request)
    {
        $search=$request->search;
        if($search)
        {
            $limit=5;
            $lists=DB::table('subcategories')->where('tags','LIKE','%'.$search.'%')->limit($limit)->get();
            $result=[];
            foreach($lists as $list)
            {
                $data['title']=$list->title;   
                $data['subreddit']=$list->link;   
                $data['slug']=$list->slug;   
                $data['link']=$list->link;   
                $data['post']=DB::table('hotposts')->select('url','post_type','post_title','thumbnail','slug')->where('subcat_id',$list->id)->where('post_type','!=','')->first(); 
                $result[]=$data;  
            }
            return response()->json(['lists'=>$result,'status'=>true]);
        }
        else{
            return response()->json(['lists'=>'','status'=>true]);
        }
    }
    function following(Request $request)
    {
        $user=$request->user();
        if($user)
        {
            $user_id=$user->id;
            $categories=DB::table('user_follow')->select('subcategories.slug','subcategories.title')->join('subcategories','user_follow.cat_id','=','subcategories.id')->get();
            return response()->json(['categories' => $categories,'status'=>true]);

        }else{
            return response()->json(['categories' => '','status'=>false]);
        }
            
    }
    function adultfollowing(Request $request)
    {
        $user=$request->user();
        if($user)
        {
            $user_id=$user->id;
            $categories=DB::table('user_follow')->select('subcategories.slug','subcategories.title')->join('subcategories','user_follow.cat_id','=','subcategories.id')->where('subcategories.cat_id','7')->get();
            return response()->json(['categories' => $categories,'status'=>true]);

        }else{
            return response()->json(['categories' => '','status'=>false]);
        }
            
    }
    function checklogin(Request $request)
    {
        $user=$request->user();
        if($user)
        {
              return response()->json(['status'=>true]);
        }else{
            return response()->json(['status'=>false]);
        }
    }
    function topfavorites(Request $request)
    {
        
        $user=$request->user();
        $user_id=$user->id;
        $q=DB::table('topposts')->select('topposts.post_title','topposts.id as post_id','topposts.post_type','topposts.url','topposts.sauce','topposts.thumbnail','topposts.permalink','topposts.slug')
        ->join('topposts_likes','topposts_likes.post_id','=','topposts.id')->where('topposts_likes.userid',$user_id);
        $posts=$q->limit($this->limit)->orderBy('topposts.id',"DESC")->get();
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$this->limit]);
    }
    function hotfavorites(Request $request)
    {
        $user=$request->user();
        $user_id=$user->id;
        $q=DB::table('hotposts')->select('hotposts.post_title','hotposts.id as post_id','hotposts.post_type','hotposts.url','hotposts.sauce','hotposts.thumbnail','hotposts.permalink','hotposts.slug')
        ->join('hotposts_likes','hotposts_likes.post_id','=','hotposts.id')->where('hotposts_likes.userid',$user_id);
        $posts=$q->limit($this->limit)->orderBy('hotposts.id',"DESC")->get();
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$this->limit]);
    }
}
