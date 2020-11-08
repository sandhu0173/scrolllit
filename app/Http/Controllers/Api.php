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
    function maincategories(Request $request)
    {
        $categories=DB::table('categories')->get();
        return response()->json(['categories' => $categories,'status'=>true]);  
    }
    function subcategories(Request $request,$slug,$offset)
    {
        $limit=3;
       $categories=DB::table('categories')->select('subcategories.slug','subcategories.title')->join('subcategories','categories.id','=','subcategories.cat_id')->where('categories.slug',$slug)->get();
       $next=$offset+$limit;
       return response()->json(['categories' => $categories,'status'=>true,'offset'=>$next]);
    }
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
    function fetchposts(Request $request,$slug)
    {
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savehotdata($sub->id);  
        //$this->savetopdata($sub->id);  
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
       $post=DB::table('posts')->where('slug',$slug)->first();
           $id=$post->id;
           $cat_id=$post->subcat_id;
           $cat=DB::table('subcategories')->select('slug')->where('id',$cat_id)
           ->first();
           $cat_slug=$cat->slug;

           $slug=$post->slug;
           $previous=DB::table('posts')->where('subcat_id',$cat_id)->where('id',"<",$id)->where('slug','!=',$slug)->orderBy('id','DESC')->first();
           $next=DB::table('posts')->where('subcat_id',$cat_id)->where('id',">",$id)->where('slug','!=',$slug)->orderBy('id','ASC')->first();
           $posts[]= $previous;
           $posts[]= $post;
           $posts[]= $next;
           if($post->istop=='1')
           {
               $type="top";
           }
           if($post->ishot=='1')
           {
               $type="hot";
           }
            return response()->json(['posts' => $posts,'type'=>$type,'cat_id'=>$cat_id,'cat_slug'=>$cat_slug]);
    }
    function newpost(Request $request,$slug )
    {
       $post=DB::table('posts')->where('slug',$slug)->first();
           $id=$post->id;
           $cat_id=$post->subcat_id;
           $slug=$post->slug;
           $next=DB::table('posts')->where('subcat_id',$cat_id)->where('id',">",$id)->where('slug','!=',$slug)->orderBy('id','ASC')->first();
           $posts[]= $next;
           if($post->istop=='1')
           {
               $type="top";
           }
           if($post->ishot=='1')
           {
               $type="hot";
           }
            return response()->json(['posts' => $posts,'type'=>$type,'cat_id'=>$cat_id]);
    }
    function newpreviouspost(Request $request,$slug )
    {
       $post=DB::table('posts')->where('slug',$slug)->first();
           $id=$post->id;
           $cat_id=$post->subcat_id;
           $slug=$post->slug;
           $prev=DB::table('posts')->where('subcat_id',$cat_id)->where('id','<',$id)->where('slug','!=',$slug)->orderBy('id','DESC')->first();
           $posts[]= $prev;
           if($post->istop=='1')
           {
               $type="top";
           }
           if($post->ishot=='1')
           {
               $type="hot";
           }
            return response()->json(['posts' => $posts,'type'=>$type,'cat_id'=>$cat_id]);
    }
    function subcatposts(Request $request,$slug,$filter,$sort,$topfilter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
       // $this->savehotdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        if($sort=="top")
        {
            $q->where('istop','1');
            if($topfilter=="today")
            {
                $day=date('Y-m-d H:i:s',strtotime('-1 day'));
                $q->where('created_at','>=',$day);
            }
            if($topfilter=="week")
            {
                $day=date('Y-m-d H:i:s',strtotime('-7 day'));
                $q->where('created_at','>=',$day);
            }
        }
        if($sort=="hot")
        {
            $q->where('ishot','1');
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
        return response()->json(['first' => $first,'second'=>$second,'third'=>$third,'status'=>true,'type'=>$sort,'offset'=>$lo]);
    }
    /*
    @get more sub category posts of a list
    */
    function moresubcatposts(Request $request,$slug,$filter,$sort,$topfilter)
    {
        $limit=$this->catlimit;
        $next=$request->offset+$limit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        if($sort=="top")
        {
            $q->where('istop','1');
            if($topfilter=="today")
            {
                $day=date('Y-m-d H:i:s',strtotime('-1 day'));
                $q->where('created_at','>=',$day);
            }
            if($topfilter=="week")
            {
                $day=date('Y-m-d H:i:s',strtotime('-7 day'));
                $q->where('created_at','>=',$day);
            }
        }
        if($sort=="hot")
        {
            $q->where('ishot','1');
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
              return response()->json(['first' => $first,'second'=>$second,'third'=>$third,'status'=>true,'type'=>$sort,'offset'=>$lo]);
    }

    function mobilesubcatposts(Request $request,$slug,$filter,$sort,$topfilter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
       // $this->savehotdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        if($sort=="top")
        {
            $q->where('istop','1');
            if($topfilter=="today")
            {
                $day=date('Y-m-d H:i:s',strtotime('-1 day'));
                $q->where('created_at','>=',$day);
            }
            if($topfilter=="week")
            {
                $day=date('Y-m-d H:i:s',strtotime('-7 day'));
                $q->where('created_at','>=',$day);
            }
        }
        if($sort=="hot")
        {
            $q->where('ishot','1');
        }
        $fo=0;
        $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
        //next offset
        $so=$fo+$limit;
        $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
        //third offset
        $to=$so+$limit;
        return response()->json(['first' => $first,'second'=>$second,'status'=>true,'type'=>$sort,'offset'=>$to]);
    }
    /*
    @get more post from sub cat of a list
    */
    function mobilemoresubcatposts(Request $request,$slug,$filter,$sort,$topfilter)
    {
        $limit=$this->catlimit;
        $next=$request->offset+$limit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('subcat_id',$sub->id);
        if($filter=="picture")
        {
            $q->where('post_type','image');
        }
        if($filter=="video")
        {
            $q->where('post_type','rich:video');
        }
        if($sort=="top")
        {
            $q->where('istop','1');
            if($topfilter=="today")
            {
                $day=date('Y-m-d H:i:s',strtotime('-1 day'));
                $q->where('created_at','>=',$day);
            }
            if($topfilter=="week")
            {
                $day=date('Y-m-d H:i:s',strtotime('-7 day'));
                $q->where('created_at','>=',$day);
            }
        }
        if($sort=="hot")
        {
            $q->where('ishot','1');
        }
              $fo=$next;
              $first=$q->offset($fo)->limit($limit)->orderBy('id',"DESC")->get();
              //next offset
              $so=$fo+$limit;
              $second=$q->offset($so)->limit($limit)->orderBy('id',"DESC")->get();
              //third offset
              $to=$so+$limit;
              return response()->json(['first' => $first,'second'=>$second,'status'=>true,'type'=>$sort,'offset'=>$to]);
        
    }
    function checklike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('postlikes')->where('post_id',$postid)->where('userid',$user_id)->count();
        $likes=DB::table('postlikes')->where('post_id',$postid)->count();
        if($check>0)
        {
            return response()->json(['status'=>true,'likes'=>$likes]);
        }else{
            return response()->json(['status'=>false,'likes'=>$likes]);
        }
        
    }
    function postlike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('postlikes')->where('post_id',$postid)->where('userid',$user_id)->count();
        if($check>0)
        {
            $post=DB::table('postlikes')->select('id')->where('post_id',$postid)->where('userid',$user_id)->first();
            DB::table('postlikes')->where('id',$post->id)->delete();
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
                        DB::table('postlikes')->insert($insert);
                        $likes=$check+1;
            return response()->json(['status'=>true,'likes'=>$likes]);
        }

    }
    function discoverposts(Request $request,$type,$slug,$filter,$offset)
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
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr);
        if($filter=='picture')
            {
                //get only image content
                $type="image";
                $q->where('post_type',$type);
            }
            if($filter=='videos')
            {
                //get only video content
                $type="rich:video";
                $q->where('post_type',$type);
            }
            if($type=='top')
            {
                $q->where('istop','1');
            }
            if($type=='hot')
            {
                $q->where('ishot','1');
            }
        $posts=$q->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
        
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next,'limit'=>$limit]);
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
                $check=DB::table('posts')->where('post_id',$post_id)->count();
                if($check==0)
                {
                $subreddit=$row->data->subreddit_name_prefixed;
                $post_title=$row->data->title;
                $comments=$row->data->num_comments;
                $url="";
                $status="1";
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
                $slug=Str::slug($post_title);
                $check2=DB::table('posts')->where('slug',$slug)->count();
                if($check2>0)
                {
                    $slug=$slug."-".$check2;
                }
                $insert=array('subcat_id'=>$subcat_id,
                          'post_id'=>$post_id,
                          'isHot'=>1,
                          'isTop'=>0,
                          'comments'=>0,
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
                
                    DB::table('posts')->insert($insert);
                }else{
                    $update=['ishot'=>'1'];
                    DB::table('posts')->where('post_id',$post_id)->update($update);
                }
            }
        }
        }
    }
    function savetopdata($id)
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
                $check=DB::table('posts')->where('post_id',$post_id)->count();
                if($check==0)
                {
                $subreddit=$row->data->subreddit_name_prefixed;
                $post_title=$row->data->title;
                $comments=$row->data->num_comments;
                $url="";
                $status="1";
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
                $slug=Str::slug($post_title);
                $check2=DB::table('posts')->where('slug',$slug)->count();
                if($check2>0)
                {
                    $slug=$slug."-".$check2;
                }
                $insert=array('subcat_id'=>$subcat_id,
                          'post_id'=>$post_id,
                          'isHot'=>'0',
                          'isTop'=>'1',
                          'comments'=>0,
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
                
                    DB::table('posts')->insert($insert);
                }else{
                    $update=['istop'=>'1'];
                    DB::table('posts')->where('post_id',$post_id)->update($update);
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
                
            }
            

        }
    }
    function topdata(Request $request,$slug,$filter)
    {
        $limit=$this->catlimit;
        $sub=DB::table('subcategories')->where('slug',$slug)->first();
        //$this->savetopdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->where('subcat_id',$sub->id);
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
       // $this->savehotdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->where('subcat_id',$sub->id);
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
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->where('subcat_id',$sub->id);
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
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->where('subcat_id',$sub->id);
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
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->where('subcat_id',$sub->id);
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
       // $this->savehotdata($sub->id);
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->where('subcat_id',$sub->id);
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
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->where('subcat_id',$sub->id);
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
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->where('subcat_id',$sub->id);
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
        
        $posts=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->whereIn('subcat_id',$sub_arr)->limit($this->limit)->orderBy('id',"DESC")->get();
        
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
        $posts=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->where('subcat_id',$sub_arr)->limit($this->limit)->orderBy('id',"DESC")->get();

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
        
        $posts=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->whereIn('subcat_id',$sub_arr)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();
        
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next]);
    }
    /*
    @get records from subreddit
    */
    function moresubcatall(Request $request,$slug,$filter,$topfilter,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        $subs=DB::table('subcategories')->where('slug',$slug)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
            $sub_arr[]=$sub->id;
        }
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->whereIn('subcat_id',$sub_arr);
            if($filter=='picture')
            {
                //get only image content
                $type="image";
                $q->where('post_type',$type);
            }
            if($filter=='videos')
            {
                //get only video content
                $type="rich:video";
                $q->where('post_type',$type);
            }
            
            $posts=$q->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();;
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next,'limit'=>$limit]);
    }

    function moresubcattop(Request $request,$slug,$filter,$topfilter,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        $subs=DB::table('subcategories')->where('slug',$slug)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
            $sub_arr[]=$sub->id;
        }
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('istop','1')->whereIn('subcat_id',$sub_arr);
            if($filter=='picture')
            {
                //get only image content
                $type="image";
                $q->where('post_type',$type);
            }
            if($filter=='videos')
            {
                //get only video content
                $type="rich:video";
                $q->where('post_type',$type);
            }
            if($topfilter=="today")
            {
                $day=date('Y-m-d H:i:s',strtotime('-1 day'));
                $q->where('created_at','>=',$day);
            }
            if($topfilter=="week")
            {
                $day=date('Y-m-d H:i:s',strtotime('-7 day'));
                $q->where('created_at','>=',$day);
            }
        
            $posts=$q->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();;
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$next,'limit'=>$limit]);
    }
    function moresubcathot(Request $request,$slug,$filter,$topfilter,$offset)
    {
        $limit=$this->limit;
        $next=$offset+$limit;
        $subs=DB::table('subcategories')->where('slug',$slug)->get();
        $sub_arr=[];
        foreach($subs as $sub)
        {
            $sub_arr[]=$sub->id;
        }
        $q=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->whereIn('subcat_id',$sub_arr);
            if($filter=='picture')
            {
                //get only image content
                $type="image";
                $q->where('post_type',$type);
            }
            if($filter=='videos')
            {
                //get only video content
                $type="rich:video";
                $q->where('post_type',$type);
            }
            
        
            $posts=$q->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();;
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
        $posts=DB::table('posts')->select('post_title','id as post_id','post_type','url','sauce','thumbnail','permalink','slug')->where('ishot','1')->where('subcat_id',$sub_arr)->offset($offset)->limit($limit)->orderBy('id',"DESC")->get();

        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$next]);
    }
        /*
    @load subreddit data
    */
    

    function checktoplike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('postlikes')->where('post_id',$postid)->where('userid',$user_id)->count();
        $likes=DB::table('postlikes')->where('post_id',$postid)->count();
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
        $check=DB::table('postlikes')->where('post_id',$postid)->where('userid',$user_id)->count();
        if($check>0)
        {
            $post=DB::table('postlikes')->select('id')->where('post_id',$postid)->where('userid',$user_id)->first();
            DB::table('postlikes')->where('id',$post->id)->delete();
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
                        DB::table('postlikes')->insert($insert);
                        $likes=$check+1;
            return response()->json(['status'=>true,'likes'=>$likes]);
        }

    }
    function checkhotlike(Request $request,$postid)
    {
        $ip=$request->ip();
        $user=$request->user();
        $user_id=$user->id;
        $check=DB::table('postlikes')->where('post_id',$postid)->where('userid',$user_id)->count();
        $likes=DB::table('postlikes')->where('post_id',$postid)->count();
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
        $check=DB::table('postlikes')->where('post_id',$postid)->where('userid',$user_id)->count();
        if($check>0)
        {
            $post=DB::table('postlikes')->select('id')->where('post_id',$postid)->where('userid',$user_id)->first();
            DB::table('postlikes')->where('id',$post->id)->delete();
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
                        DB::table('postlikes')->insert($insert);
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
                $data['post']=DB::table('posts')->select('url','post_type','post_title','thumbnail','slug')->where('subcat_id',$list->id)->where('post_type','!=','')->first(); 
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
        $q=DB::table('posts')->select('posts.post_title','posts.id as post_id','posts.post_type','posts.url','posts.sauce','posts.thumbnail','posts.permalink','posts.slug')
        ->join('postlikes','postlikes.post_id','=','posts.id')->where('posts.istop','1')->where('postlikes.userid',$user_id);
        $posts=$q->limit($this->limit)->orderBy('posts.id',"DESC")->get();
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'top','offset'=>$this->limit]);
    }
    function hotfavorites(Request $request)
    {
        $user=$request->user();
        $user_id=$user->id;
        $q=DB::table('posts')->select('posts.post_title','posts.id as post_id','posts.post_type','posts.url','posts.sauce','posts.thumbnail','posts.permalink','posts.slug')->where('posts.ishot','1')
        ->join('postlikes','postlikes.post_id','=','posts.id')->where('postlikes.userid',$user_id);
        $posts=$q->limit($this->limit)->orderBy('posts.id',"DESC")->get();
        return response()->json(['posts' => $posts,'status'=>true,'type'=>'hot','offset'=>$this->limit]);
    }
}
