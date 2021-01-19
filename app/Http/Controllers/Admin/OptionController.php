<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Option;
use Redirect;
use Arr;
use DB;
class OptionController extends Controller
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
        return view('admin/options/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type']='create';
        return view('admin/options/form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), ['option_name' => 'required','display_order' => 'required']); 
        $input=$request->all();
        Arr::forget($input,['_token','status']);
        $input['published']=($request->status=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        Option::insert($input);
        return Redirect::route('options.index')->with('success','New Option added successfully.!');
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
        $data['option']=Option::find($id);
        $data['type']="edit";
        return view('admin/options/form',$data);
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
        $this->validate(request(), ['option_name' => 'required','display_order' => 'required']);
        $status=($request->status=='on')?1:0;
        $option=Option::find($id);
        $option->option_name=$request->option_name;
        $option->display_order=$request->display_order;
        $option->published=$status;
        $option->save();
        return Redirect::route('options.index')->with('info','Option updated successfully.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $option=Option::find($id);
        $option->is_deleted=1;
        $option->deleted_at = date('Y-m-d H:i:s');
        $option->save();

        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('options.index')->with('error','Option deleted successfully...!');
    }
}
