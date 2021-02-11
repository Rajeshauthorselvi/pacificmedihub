<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use Redirect;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin/categories/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin/categories/create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'category_name'  => 'required',
            'search_engine'  => 'required'
        ]);

        if($request->category_published){$published = 1;}else{$published = 0;}
        if($request->category_homepage){$homepage = 1;}else{$homepage = 0;}

        $image= $request->hasFile('category_image');
        if($image){
            $photo          = $request->file('category_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('category_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->category_image->move(public_path('theme/images/categories/'), $image_name);
        }
        else{
            $image_name = NULL;
        }
        $add = new Categories;
        $add->parent_category_id = $request->parent_category;
        $add->name   = $request->category_name;
        $add->image  = $image_name;
        $add->description = $request->category_description;
        $add->published = $published;
        $add->show_home = $homepage;
        $add->display_order = $request->display_order;
        $add->search_engine_name = $request->search_engine;
        $add->meta_title = $request->meta_title;
        $add->meta_keyword = $request->meta_keyword;
        $add->meta_description = $request->meta_description;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();

        if($add) return redirect()->route('categories.index')->with('success','New Category added successfully...!');
        return Redirect::back()->with('error','Somthing wrong please try again...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['category_list'] = Categories::where('is_deleted',0)->orderBy('created_at','desc')->get();
        $data['category'] = Categories::find($id);
        return view('admin/categories/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'category_name'  => 'required',
            'search_engine'  => 'required'
        ]);
        
        if($request->category_published){$published = 1;}else{$published = 0;}
        if($request->category_homepage){$homepage = 1;}else{$homepage = 0;}

        $check_category=Categories::find($id);

        $image= $request->hasFile('category_image');
        if($image){
            $photo          = $request->file('category_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('category_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->category_image->move(public_path('theme/images/categories/'), $image_name);
        }
        else{
            if($check_category){
                $image_name = $check_category->image;    
            }else{
                $image_name = NULL;    
            }
        }

        if($check_category){
            $check_category->parent_category_id = $request->parent_category;
            $check_category->name   = $request->category_name;
            $check_category->image  = $image_name;
            $check_category->description = $request->category_description;
            $check_category->published = $published;
            $check_category->show_home = $homepage;
            $check_category->display_order = $request->display_order;
            $check_category->search_engine_name = $request->search_engine;
            $check_category->meta_title = $request->meta_title;
            $check_category->meta_keyword = $request->meta_keyword;
            $check_category->meta_description = $request->meta_description;
            $check_category->update();
            return redirect()->route('categories.index')->with('success','Category modified successfully...!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!');    
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $check_category=Categories::find($id);
        if($check_category){
            $check_category->published  = 0;
            $check_category->show_home  = 0;
            $check_category->is_deleted = 1;
            $check_category->deleted_at = date('Y-m-d H:i:s');
            $check_category->update();
        }
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('categories.index')->with('error','Category deleted successfully...!');
    }
}
