<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
use Arr;
use Redirect;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['taxes'] = Tax::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/settings/tax/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type']='create';
        $data['tax_type']=[''=>'Please Select','p'=>'Percentage (%)','f'=>'Fixed (amount)'];
        return view('admin/settings/tax/form',$data);
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
            'name'     => 'required',
            'tax_type' => 'required',
            'rate'     => 'required'
        ]); 

        $input = $request->all();
        Arr::forget($input,['_token','published']);
        $input['published']=($request->published=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        Tax::insert($input);
        return Redirect::route('tax.index')->with('success','Tax added successfully...!');
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
        $data = array();
        $data['type']='edit';
        $data['tax']=tax::where('id',$id)->first();
        $data['tax_type']=[''=>'Please Select','p'=>'Percentage (%)','f'=>'Fixed (amount)'];
        
        return view('admin/settings/tax/form',$data);
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
            'name'     => 'required',
            'tax_type' => 'required',
            'rate'     => 'required'
        ]); 

        $published=($request->published=='on')?1:0;

        $update_tax = Tax::find($id);
        $update_tax->name = $request->name;
        $update_tax->code = $request->code;
        $update_tax->tax_type = $request->tax_type;
        $update_tax->rate = $request->rate;
        $update_tax->published = $published;
        $update_tax->update();

        return Redirect::route('tax.index')->with('info','Tax updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $check_tax = Tax::find($id);
        $check_tax->is_deleted = 1;
        $check_tax->deleted_at=date('Y-m-d H:i:s');
        $check_tax->update();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('tax.index')->with('error','Tax deleted successfully...!');
    }
}
