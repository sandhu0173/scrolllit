<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Str;
class SubCategoriesController extends Controller
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
    public function index(Request $request,$id)
    {
        $data['categories']=DB::table('subcategories')->where('cat_id',$id)->get();
        $data['count']=1;
        $data['cat_id']=$id;
        return view('admin.subcategory.index',$data);
    }
    public function create(Request $request,$id)
    {
        if($request->method()=="POST")
        {

            $slug=Str::slug($request->title);
             $cover="";
            if($request->has('cover'))
            {
                $file1 = time().'.'.$request->cover->extension(); 
                $request->cover->move(public_path('uploads/covers/'), $file1);
                $cover='uploads/covers/'.$file1;
            }
            $profile="";
            if($request->has('profile'))
            {
                $file2 = time().'.'.$request->profile->extension(); 
                $request->profile->move(public_path('uploads/profiles/'), $file2);
                $profile='uploads/profiles/'.$file2;
            }
                
            $insert=array('title'=>$request->title,
            'cat_id'=>$request->cat_id,
            'link'=>$request->link,
            'cover'=>$cover,
            'cover'=>$cover,
            'profile'=>$profile,
            'slug'=>$slug,
            'description'=>$request->description,
            'tags'=>$request->tags,
            'status'=>'1',
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
                    );
            DB::table('subcategories')->insert($insert);
            $request->session()->flash('success', 'Sub Reddit added successful!');
            return redirect(route('sub-categories',['id'=>$request->cat_id]));

        }
        $data['cat_id']=$id;
        return view('admin.subcategory.create',$data);
    }
    public function edit(Request $request,$id)
    {
        if($request->method()=="POST")
        {
            $slug=Str::slug($request->title);
            
            $update=array('title'=>$request->title,
            'cat_id'=>$request->cat_id,
            'link'=>$request->link,
            'slug'=>$slug,
            'description'=>$request->description,
            'tags'=>$request->tags,
            'updated_at'=>date('Y-m-d H:i:s')
            );
            if($request->has('cover'))
            {
                $file1 = time().'.'.$request->cover->extension(); 
                $request->cover->move(public_path('uploads/covers/'), $file1);
                $cover='uploads/covers/'.$file1;
                $update['cover']=$cover;
            }
            if($request->has('profile'))
            {
                $file2 = time().'.'.$request->profile->extension(); 
                $request->profile->move(public_path('uploads/profiles/'), $file2);
                $profile='uploads/profiles/'.$file2;
                $update['profile']=$profile;
            }

            DB::table('subcategories')->where('id',$id)->update($update);
            $request->session()->flash('success', 'Sub Reddit updated successful!');
            return redirect(route('sub-categories',['id'=>$request->cat_id]));

        }
        $data['category']=DB::table('subcategories')->find($id);
        return view('admin.subcategory.edit',$data);
    }
    public function delete(Request $request,$id)
    {
        DB::table('subcategories')->where('id',$id)->delete();
        $request->session()->flash('success', 'Sub reddit deleted successful!');
        return redirect(route('sub-categories',['id'=>$request->cat_id]));
    }
}
