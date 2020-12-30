<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionValue;
use App\Models\Commissions;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Arr;
class ComissionValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['comission_values']=CommissionValue::with('comission')->where('commission_values.status','<>',3)->get();

        return view('admin.commission_values.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type']='create';
        $data['commissions']=[''=>'Please Select']+Commissions::where('status',1)->pluck('commission_name','id')->toArray();
        $data['commission_type']=[''=>'Please Select','p'=>'Percentage','f'=>'Fixed'];
        return view('admin.commission_values.form',$data);
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
            'commission_id' => 'required',
            'commission_type' => 'required',
            'commission_value' => 'required'
        ]); 


        $input=$request->all();
        Arr::forget($input,['_token','status']);
        $input['status']=($request->status=='on')?1:2;
        CommissionValue::insert($input);
        return Redirect::route('comission_value.index')->with('success','Commission value added successfully...!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComissionValue  $comissionValue
     * @return \Illuminate\Http\Response
     */
    public function show(CommissionValue $commissionValue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ComissionValue  $comissionValue
     * @return \Illuminate\Http\Response
     */
    public function edit(CommissionValue $comissionValue)
    {
        $data['commission_value']=CommissionValue::with('comission')->where('commission_values.id',$comissionValue->id)->first();
        $data['type']='edit';
        $data['commissions']=[''=>'Please Select']+Commissions::where('status',1)->pluck('commission_name','id')->toArray();
        $data['commission_type']=[''=>'Please Select','p'=>'Percentage','f'=>'Fixed'];
        return view('admin.commission_values.form',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ComissionValue  $comissionValue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CommissionValue $comissionValue)
    {
        $this->validate(request(), [
            'commission_id' => 'required',
            'commission_type' => 'required',
            'commission_value' => 'required'
        ]); 

        $status=($request->status=='on')?1:2;
        $comission_value=CommissionValue::find($comissionValue->id);
        $comission_value->commission_id=$request->commission_id;
        $comission_value->commission_type=$request->commission_type;
        $comission_value->commission_value=$request->commission_value;
        $comission_value->status=$status;
        $comission_value->save();

        return Redirect::route('comission_value.index')->with('success','Commission value updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ComissionValue  $comissionValue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,CommissionValue $comissionValue)
    {
    
       $comission_value=CommissionValue::find($comissionValue->id);
       $comission_value->status=3;
       $comission_value->save();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('comission_value.index')->with('success','Commission value deleted successfully...!');
    }
}
