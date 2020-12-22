<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductUnit;
use Redirect;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['product_units'] = ProductUnit::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin/productUnit/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/productUnit/create');
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
            'unit_name' => 'required',
            'unit_code' => 'required'
        ]); 
        
        if($request->unit_published){$published = 1;}else{$published = 0;}
        $add = new ProductUnit;
        $add->unit_name  = $request->unit_name;
        $add->unit_code  = $request->unit_code;
        $add->published  = $published;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();

        if($add) return redirect()->route('product-units.index')->with('success','New Unit added successfully...!');
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
        $data['unit'] = ProductUnit::find($id);
        return view('admin/productUnit/edit',$data);
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
            'unit_name' => 'required',
            'unit_code' => 'required'
        ]); 
        $check_unit=ProductUnit::find($id);
        if($request->unit_published){$published = 1;}else{$published = 0;}

        if($check_unit){
            $check_unit->unit_name  = $request->unit_name;
            $check_unit->unit_code  = $request->unit_code;
            $check_unit->published  = $published;
            $check_unit->update();    
            return redirect()->route('product-units.index')->with('success','Unit modified successfully...!');
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
    public function destroy($id)
    {
        $check_unit=ProductUnit::find($id);
        if($check_unit){
            $check_unit->published  = 0;
            $check_unit->is_deleted = 1;
            $check_unit->deleted_at = date('Y-m-d H:i:s');
            $check_unit->update();
        }
        return redirect()->route('product-units.index')->with('error','Unit deleted successfully...!');
    }
}
