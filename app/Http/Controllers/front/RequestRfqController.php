<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\UserAddress;
use App\Models\Countries;
use App\User;
use Auth;
use Str;
use Session;

class RequestRfqController extends Controller
{
    public function index()
    {
        if(Auth::check()){
        	$user_id   = Auth::id();

        	$primary = $data['primary_id'] = Auth::user()->address_id;
            $data['primary']     = UserAddress::where('id',$primary)->where('customer_id',$user_id)->first();
            $data['delivery'] = UserAddress::where('address_type',1)->where('customer_id',$user_id)->first();
            $data['all_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('address_type',[1,2])
            								  ->where('is_deleted',0)->get();
            $data['billing_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('id',[$primary])
            									  ->where('is_deleted',0)->get();
            $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();

            $cart_data = [];
            Cart::instance('cart')->restore('userID_'.$user_id);
            $cart_items = Cart::content();
            foreach($cart_items as $key => $items)
            {
                $cart_data[$key]['uniqueId']      = $items->getUniqueId();
                $cart_data[$key]['product_id']    = $items->id;
                $cart_data[$key]['product_name']  = $items->name;
                $cart_data[$key]['product_image'] = $items->options['product_img'];
                $cart_data[$key]['qty']           = $items->quantity;
                $cart_data[$key]['variant_id']    = $items->options['variant_id'];
            }
            $data['user_id'] = $user_id;
            $data['cart_count'] = Cart::count();
            $data['cart_data'] = $cart_data;
            return view('front/customer/proceed_rfq',$data);
        }else{
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        
    }
}
