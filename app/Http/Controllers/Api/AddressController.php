<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserAddress;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_address=UserAddress::where('customer_id',Auth::id())->get();
        foreach ($all_address as $key => $address) {
            $country_name=$state_name=$city_name=null;
            if (isset($address->country_id)) {
                $country_name = Countries::where('id',$address->country_id)->value('name');
            }
            if (isset($address->state_id)) {
                $state_name = State::where('id',$address->state_id)->value('name');
            }
            if (isset($address->city_id)) {
                $city_name= City::where('id',$address->city_id)->value('name');
            }
            $address->address_type=($address->address_type==1)?true:false;
            $address->country_name=$country_name;
            $address->state_name=$state_name;
            $address->city_name=$city_name;
        }
        return response()->json($all_address);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function countryList()
    {
        $state = Countries::select('id','name')->orderBy('name','asc')->get()->toArray();    
        return response()->json($state);
    }

    public function stateORCityList(Request $request,$id)
    {
        if($request->from=='state'){
            $list = State::select('id','name')->where('country_id',$id)->get()->toArray();
        }elseif($request->from=='city'){
            $list = City::select('id','name')->where('state_id',$id)->get()->toArray();    
        }
        return response()->json($list);
    }

    public function DeleteAddress($address_id)
    {
        UserAddress::where('customer_id',Auth::id())
        ->where('id',$address_id)
        ->update(['is_deleted'=>1,'deleted_at'=>date('Y-m-d H:i:s')]);
        
        return response()->json(['success'=> true,'data'=>[]]);
    }   

}
