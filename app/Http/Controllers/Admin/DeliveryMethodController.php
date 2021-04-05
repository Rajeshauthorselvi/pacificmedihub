<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMethod;
use Illuminate\Http\Request;
use Redirect;
class DeliveryMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['all_delivery_methods']=DeliveryMethod::where('status','<>',3)->orderBy('id','desc')->get();

        return view('admin.delivery_methods.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=array();
        return view('admin.delivery_methods.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(),[
            'delivery_method' => 'required',
            'amount'          => 'required',
            'target_amount'   => 'required'
        ]);
        DeliveryMethod::insert([
            'delivery_method' => $request->delivery_method,
            'amount'          => $request->amount,
            'target_amount'   => $request->target_amount,
            'status'          => ($request->published=="on")?1:2
        ]);
        return Redirect::route('delivery-methods.index')->with('success','Delivery method added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryMethod  $deliveryMethod
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryMethod $deliveryMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryMethod  $deliveryMethod
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryMethod $deliveryMethod)
    {
        $data=array();
        $data['delivery_method']=DeliveryMethod::find($deliveryMethod->id);
        return view('admin.delivery_methods.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryMethod  $deliveryMethod
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryMethod $deliveryMethod)
    {
        $this->validate(request(),[
            'delivery_method' => 'required',
            'amount'          => 'required',
            'target_amount'   => 'required'
        ]);
        DeliveryMethod::where('id',$deliveryMethod->id)->update([
            'delivery_method' => $request->delivery_method,
            'amount'          => $request->amount,
            'target_amount'   => $request->target_amount,
            'status'          => ($request->published=="on")?1:2
        ]);
        return Redirect::route('delivery-methods.index')->with('success','Delivery method updated successfully');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryMethod  $deliveryMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, DeliveryMethod $deliveryMethod)
    {
       $deliveryMethod=DeliveryMethod::find($deliveryMethod->id);
       $deliveryMethod->status=3;
       $deliveryMethod->save();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('delivery-methods.index')->with('success','DeliveryZone deleted successfully...!');
    }
}
