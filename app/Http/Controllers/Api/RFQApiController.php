<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RFQ;
use App\Models\RFQProducts;
use App\Models\UserAddress;
use App\Models\DeliveryMethod;
use App\Models\Product;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use Auth;
use Validator;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
class RFQApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $user_id    = Auth::id();
            $primary_id = Auth::user()->address_id;

            /*$data['all_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('address_type',[1,2])
                                              ->where('is_deleted',0)->get();*/

            $delivery   = UserAddress::where('address_type',1)->where('customer_id',$user_id)->first();
            $primary    = UserAddress::where('id',$primary_id)->where('customer_id',$user_id)->first();

            if(isset($delivery)){
                $default_shipping_address = $delivery;
                $remove_id        = $delivery->id;
            }else{
                $default_shipping_address = $primary;
                $remove_id        = $primary_id;
            }
            $data['shipping_address']=$default_shipping_address;
            $data['billing_address']=$default_shipping_address;


            /*$data['billing_address'] = UserAddress::where('customer_id',$user_id)
                                       ->whereNotIn('id',[$remove_id])
                                       ->where('is_deleted',0)->get();*/
         /*   $data['all_address'] = UserAddress::where('customer_id',$user_id)
                                       ->where('is_deleted',0)
                                       ->get();*/

            // $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
            // $data['delivery_method'] = DeliveryMethod::where('is_free_delivery','no')->where('status',1)->get();
            Cart::instance('cart')->restore('userID_'.$user_id);

            $cart_data = [];

            $cart_items = Cart::content();
            $cart_loop=0;
            foreach($cart_items as $key => $items)
            {
                $product = Product::find($items->id);
                
                $cart_data[$cart_loop]['uniqueId']      = $items->getUniqueId();
                $cart_data[$cart_loop]['product_id']    = $items->id;
                $cart_data[$cart_loop]['product_name']  = $items->name;
                $cart_data[$cart_count]['product_code']  = $product->code;
                $cart_data[$cart_loop]['product_sku']   = $items->options['variant_sku'];
                $cart_data[$cart_loop]['product_image'] = $items->options['product_img'];
                $cart_data[$cart_loop]['qty']           = $items->quantity;
                $cart_data[$cart_loop]['variant_id']    = $items->options['variant_id'];

                $category_slug = $product->category->search_engine_name;
                $product_id = base64_encode($product->id);
                $url = url("$category_slug/$product->search_engine_name/$product_id");
                $cart_data[$cart_loop]['link'] = $url;

                $cart_loop++;
            }
            $data['user_id']    = $user_id;
            $data['cart_count'] = Cart::count();
            $data['cart_data']  = $cart_data;
        return response()->json(['success'=> true,'data'=>$data]);
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
    public function AddAddress(Request $request)
    {
        $rules =[
            'name'  => 'required',
            'mobile'  => 'required',
            'address_line1'  => 'required',
            'country_id'  => 'required',
            'latitude'  => 'required',
            'longitude'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }
         $address=[
            'customer_id'       => Auth::id(),
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'address_line1'     => $request->address_line1,
            'address_line2'     => $request->address_line2,
            'country_id'        => $request->country_id,
            'state_id'          => $request->state_id,
            'city_id'           => $request->city_id,
            'post_code'         => $request->post_code,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude
        ];

        $check_order_exists=UserAddress::where('customer_id',Auth::id())->count();
        $address['address_type']=1;
        if ($check_order_exists!=0 && $request->is_default=="yes") {
            UserAddress::where('customer_id',Auth::id())->update(['address_type'=>1]);
            $address['address_type']=0;
        }
        elseif ($check_order_exists==0) {
            $address['address_type']=0;
        }
        $address['created_at']=date('Y-m-d H:i:s');
        $address_id=UserAddress::insertGetId($address);
        $address=UserAddress::find($address_id);
        return response()->json(['success'=> true,'data'=>$address]);
    }
    public function UpdateAddress(Request $request)
    {
        // dd(Auth::id());
        $rules =[
            'address_id'    => 'required',
            'name'          => 'required',
            'mobile'        => 'required',
            'address_line1' => 'required',
            'country_id'    => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }

        $address_id=$request->address_id;
         $address=[
            'customer_id'       => Auth::id(),
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'address_line1'     => $request->address_line1,
            'address_line2'     => $request->address_line2,
            'country_id'        => $request->country_id,
            'state_id'          => $request->state_id,
            'city_id'           => $request->city_id,
            'post_code'         => $request->post_code,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude
        ];
/*        if($request->is_default=="yes"){
            UserAddress::where('customer_id',Auth::id())->where('address_type',0)->update(['address_type'=>1]);
            $address['address_type']=0;
        }*/
        UserAddress::where('id',$address_id)->update($address);
        $address=UserAddress::find($address_id);
         return response()->json(['success'=> true,'data'=>$address]);
    }
    public function UpdatePrimaryAddress($address_id)
    {
        UserAddress::where('customer_id',Auth::id())->where('address_type',0)->update(['address_type'=>1]);
        UserAddress::where('customer_id',Auth::id())->where('id',$address_id)->update(['address_type'=>0]);
        return response()->json(['success'=> true,'data'=>[]]);
    }

    public function AllCountries()
    {
        return Countries::get()->toArray();
    }
    public function StatesBasedCountry($country_id='')
    {
        return State::where('country_id',$country_id)->get()->toArray();
    }
    public function CityBasedState($state_id='')
    {
        return City::where('state_id',$state_id)->get()->toArray();
    }
}
