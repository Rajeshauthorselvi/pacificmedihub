<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\Region;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Arr;
use Auth;


class DeliveryZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('delivery_zone','read')) {
                abort(404);
            }
        }
        $data=array();
        $data['regions'] = Region::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin.delivery_zone.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('delivery_zone','create')) {
                abort(404);
            }
        }
        return view('admin.delivery_zone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), ['region_name' => 'required']); 
        
        $status=($request->status=='on')?1:0;
        $add_region = new Region;
        $add_region->name       = $request->region_name;
        $add_region->published  = $status;
        $add_region->created_at = date('Y-m-d H:i:s');
        $add_region->save();

        if($add_region){
            if($request->post_code){
                $values_data = [];
                $i = 0;
                foreach ($request->post_code['name'] as $name) {
                    $values_data[$i]['name'] = $name;
                    $i = $i+1;
                }

                $i = 0;
                foreach ($request->post_code['published'] as $published) {
                    $values_data[$i]['published'] = $published;
                    $i = $i+1;
                }
            }

            foreach ($values_data as $values) {
                $add_zone = new DeliveryZone;
                $add_zone->region_id  = $add_region->id;
                $add_zone->post_code  = $values['name'];
                $add_zone->published  = $values['published'];
                $add_zone->timestamps = false;
                $add_zone->save();
            }
        }
        return Redirect::route('delivery_zone.index')->with('success','Delivery Zone added successfully...!');
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
    public function edit($id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('delivery_zone','update')) {
                abort(404);
            }
        }

        $data['region'] = Region::find($id);
        $data['post_codes'] = $post_codes = DeliveryZone::where('region_id',$id)->get();
        $data['values_count']  = count($post_codes);
        return view('admin/delivery_zone/edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryZone  $deliveryZone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       $this->validate(request(), ['post_code' => 'required','delivery_fee' => 'required']); 

       $status=($request->status=='on')?1:0;
       $zone=DeliveryZone::find($deliveryZone->id);
       $zone->post_code=$request->post_code;
       $zone->delivery_fee=$request->delivery_fee;
       $zone->published=$status;
       $zone->save();

       return Redirect::route('delivery_zone.index')->with('success','Delivery Zone details updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryZone  $deliveryZone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
       $region = Region::find($id);
       $region->is_deleted = 1;
       $region->deleted_at = date('Y-m-d H:i:s');
       $region->save();

        if ($request->ajax())  return ['status'=>true];
        else
        return Redirect::route('delivery_zone.index')->with('success','DeliveryZone deleted successfully...!');
    }

    public function deletePostCode(Request $request)
    {
        DeliveryZone::where('id',$request->id)->delete();
        return ['status'=>true];
    }
}
