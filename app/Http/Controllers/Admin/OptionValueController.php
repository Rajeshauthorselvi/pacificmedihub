<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\OptionValue;
use Redirect;
use Arr;

class OptionValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['product_options']=Option::where('is_deleted',0)->orderBy('created_at','desc')->get();
        $data['option_values'] = OptionValue::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin/option_value/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type']='create';
        $data['product_options']=[''=>'Please Select']+Option::where('published',1)->where('is_deleted',0)->pluck('option_name','id')->toArray();
        return view('admin/option_value/form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), ['option_id'=>'required','option_value'=>'required','display_order'=>'required']);
        $input=$request->all();
        Arr::forget($input,['_token']);
        $input['created_at'] = date('Y-m-d H:i:s');
        OptionValue::insert($input);
        return Redirect::route('option_values.index')->with('success','New Option Value added successfully.!');
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
        $data['option_values']=OptionValue::find($id);
        $data['type']='edit';
        $data['product_options']=[''=>'Please Select']+Option::where('published',1)->where('is_deleted',0)->pluck('option_name','id')->toArray();
        return view('admin/option_value/form',$data);
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
        $this->validate(request(), ['option_id'=>'required','option_value'=>'required','display_order'=>'required']);
        $value=OptionValue::find($id);
        $value->option_id=$request->option_id;
        $value->option_value=$request->option_value;
        $value->display_order=$request->display_order;
        $value->update();
        return Redirect::route('option_values.index')->with('info','Option Value updated successfully.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $value=OptionValue::find($id);
        $value->is_deleted=1;
        $value->deleted_at = date('Y-m-d H:i:s');
        $value->save();

        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('option_values.index')->with('error','Option Value deleted successfully...!');
    }
}
