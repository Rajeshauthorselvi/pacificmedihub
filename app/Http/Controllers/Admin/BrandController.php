<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Countries;
use Redirect;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['brands'] = Brand::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/brands/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        return view('admin/brands/create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), ['brand_name' => 'required', 'manf_name' => 'required']); 

        if($request->brand_published){$published = 1;}else{$published = 0;}
        
        $image= $request->hasFile('brand_image');
        if($image){
            $photo          = $request->file('brand_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('brand_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->brand_image->move(public_path('theme/images/brands/'), $image_name);
        }
        else{
            $image_name = NULL;
        }
        $add = new Brand;
        $add->name   = $request->brand_name;
        $add->manf_name = $request->manf_name;
        $add->manf_country_id = $request->country_id;
        $add->image  = $image_name;
        $add->published = $published;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();

        if($add) return redirect()->route('brands.index')->with('success','New Brand added successfully...!');
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
        $data['brand'] = Brand::find($id);
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        return view('admin/brands/edit',$data);
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
        $this->validate(request(), ['brand_name' => 'required', 'manf_name' => 'required']); 
        
        if($request->brand_published){$published = 1;}else{$published = 0;}
        
        $check_brand=brand::find($id);

        $image= $request->hasFile('brand_image');
        if($image){
            $photo          = $request->file('brand_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('brand_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->brand_image->move(public_path('theme/images/brands/'), $image_name);
        }
        else{
            if($check_brand){
                $image_name = $check_brand->image;    
            }else{
                $image_name = NULL;    
            }
            
        }
        if($check_brand){
            $check_brand->name   = $request->brand_name;
            $check_brand->manf_name = $request->manf_name;
            $check_brand->manf_country_id = $request->country_id;
            $check_brand->image  = $image_name;
            $check_brand->published = $published;
            $check_brand->save();
            return redirect()->route('brands.index')->with('info','Brand modified successfully...!');
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
        $check_brand=brand::find($id);
        if($check_brand){
            $check_brand->published  = 0;
            $check_brand->is_deleted = 1;
            $check_brand->deleted_at = date('Y-m-d H:i:s');
            $check_brand->update();
        }
         if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('brands.index')->with('error','Brand deleted successfully...!');
    }
}
