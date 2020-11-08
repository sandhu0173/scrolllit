<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Str;
class CategoriesController extends Controller
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
        $data['categories']=DB::table('categories')->get();
        $data['count']=1;
        return view('admin.category.index',$data);
    }
    public function create(Request $request)
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
            'color'=>$request->color,
            'slug'=>$slug,
            'cover'=>$cover,
            'profile'=>$profile,
            'status'=>'1',
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s')
                    );
            DB::table('categories')->insert($insert);
            $request->session()->flash('success', 'Category added successful!');
            return redirect(route('categories'));

        }
        return view('admin.category.create');
    }
    public function edit(Request $request,$id)
    {
        if($request->method()=="POST")
        {
            $slug=Str::slug($request->title);
            $update=array('title'=>$request->title,
            'color'=>$request->color,
            'slug'=>$slug,
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
            DB::table('categories')->where('id',$id)->update($update);
            $request->session()->flash('success', 'Category updated successful!');
            return redirect(route('categories'));

        }
        $data['category']=DB::table('categories')->find($id);
        return view('admin.category.edit',$data);
    }
    public function delete(Request $request,$id)
    {
        DB::table('categories')->where('id',$id)->delete();
        $request->session()->flash('success', 'Category deleted successful!');
        return redirect(route('categories'));
    }
}
