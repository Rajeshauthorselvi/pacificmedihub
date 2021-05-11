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
use Auth;
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
                $data['default_delivery'] = $delivery;
                $remove_id        = $delivery->id;
            }else{
                $data['default_delivery'] = $primary;
                $remove_id        = $primary_id;
            }

            $data['billing_address'] = UserAddress::where('customer_id',$user_id)
                                       ->whereNotIn('id',[$remove_id])
                                       ->where('is_deleted',0)->get();
            $data['all_address'] = UserAddress::where('customer_id',$user_id)
                                       ->where('is_deleted',0)
                                       ->get();

            // $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
            $data['delivery_method'] = DeliveryMethod::where('is_free_delivery','no')->where('status',1)->get();
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
}
