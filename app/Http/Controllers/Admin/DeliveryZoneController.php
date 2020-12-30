<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Arr;
class DeliveryZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['delivery_zones']=DeliveryZone::where('is_deleted',0)->get();
        return view('admin.delivery_zone.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['type']='create';
        return view('admin.delivery_zone.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(request(), ['post_code' => 'required','delivery_fee' => 'required']); 

        $input=$request->all();
        Arr::forget($input,['_token','status']);
        $input['published']=($request->status=='on')?1:0;
        $input['created_at'] = date('Y-m-d H:i:s');
        DeliveryZone::insert($input);
        return Redirect::route('delivery_zone.index')->with('success','DeliveryZone added successfully...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryZone  $deliveryZone
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryZone $deliveryZone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryZone  $deliveryZone
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryZone $deliveryZone)
    {
        $data['zone']=DeliveryZone::find($deliveryZone->id);
        $data['type']="edit";
        return view('admin.delivery_zone.form',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryZone  $deliveryZone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryZone $deliveryZone)
    {

       $this->validate(request(), ['post_code' => 'required','delivery_fee' => 'required']); 

       $status=($request->status=='on')?1:0;
       $zone=DeliveryZone::find($deliveryZone->id);
       $zone->post_code=$request->post_code;
       $zone->delivery_fee=$request->delivery_fee;
       $zone->published=$status;
       $zone->save();

       return Redirect::route('delivery_zone.index')->with('success','DeliveryZone details updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryZone  $deliveryZone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,DeliveryZone $deliveryZone)
    {
       $zone=DeliveryZone::find($deliveryZone->id);
       $zone->is_deleted=1;
       $zone->deleted_at = date('Y-m-d H:i:s');
       $zone->save();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('delivery_zone.index')->with('success','DeliveryZone deleted successfully...!');
    }
}
