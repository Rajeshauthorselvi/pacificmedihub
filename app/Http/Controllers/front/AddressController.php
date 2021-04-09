<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserAddress;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use Auth;
use Arr;
use Redirect;
use Session;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check()){
            $user_id = Auth::id();
            $primary = $data['primary_id'] = Auth::user()->address_id;
            $data['primary'] = UserAddress::where('id',$primary)->where('customer_id',$user_id)->first();
            $data['all_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('id',[$primary])
                                                ->whereNotIn('address_type',[2])->where('is_deleted',0)->paginate(5);
            $data['user_id'] = $user_id;
            return view('front/customer/address/index',$data);
        }else{
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::check()){
            $user_id = Auth::id();
            $data['user_id'] = $user_id;
            $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
            return view('front/customer/address/create',$data);
        }else{
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');   
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }        
        $address     = $request->address;
        $add_address = UserAddress::insertGetId($address);
        
        if($add_address){
            if ($request->ajax()){
                if($request->address_type==1){
                    UserAddress::where('address_type',1)->update(['address_type'=>0]);
                    $change_delivery = UserAddress::find($add_address);
                    $change_delivery->address_type = 1;
                    $change_delivery->save();
                }
                $data = $add_address;
                return response()->json($data);
            }
            return redirect()->route('my-address.index')->with('success', 'New address added Successfully.!');
        }else{
            if ($request->ajax()){
                $data = 'error';
                return response()->json($data);
            }
            return Redirect::back()->with('error','Somthing wrong please try again.!');
        }    
        
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
        if(Auth::check()){
            $user_id = Auth::id();
            $data['user_id'] = $user_id;
            $data['address'] = UserAddress::find($id);
            $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
            return view('front/customer/address/edit',$data);
        }else{
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');   
        }
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
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $address                = UserAddress::find($id);
        $address->name          = $request->name;
        $address->mobile        = $request->mobile;
        $address->address_line1 = $request->address_line1;
        $address->address_line2 = $request->address_line2;
        $address->post_code     = $request->post_code;
        $address->country_id    = $request->country_id;
        $address->state_id      = $request->state_id;
        $address->city_id       = $request->city_id;
        $address->latitude      = $request->latitude;
        $address->longitude     = $request->longitude;
        $address->update();

        if($address){
            return redirect()->route('my-address.index')->with('info', 'Address Modified Successfully.!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again.!');
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
        $address = UserAddress::where('id',$id)->first();
        $address->is_deleted = 1;
        $address->deleted_at = date('y-m-d H:i:s');
        $address->update();

        return redirect()->route('my-address.index')->with('info', 'Address removed Successfully.!');

    }

    public function setPrimaryAddress(Request $request, $address_id)
    {
        if(Auth::check()){

            if ($request->ajax()){
                UserAddress::where('address_type',1)->update(['address_type'=>0]);
                $change_delivery = UserAddress::find($request->delivery_address_id);
                $change_delivery->address_type = 1;
                $change_delivery->save();
                return response()->json(true);
            }
            $user_id = Auth::id();
            User::where('id',$user_id)->update(['address_id'=>$address_id]);
            return redirect()->route('my-address.index');
        }else{
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
    }

    public function getStateList(Request $request)
    {
        $state = State::where('country_id',$request->country_id)->pluck("name","id");    
        return response()->json($state);
    }

    public function getCityList(Request $request)
    {
        $state = City::where('state_id',$request->state_id)->pluck("name","id");    
        return response()->json($state);
    }
}
