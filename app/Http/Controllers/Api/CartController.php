<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Auth;
use Str;
use Session;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::check())
        {
            $cart_data = [];
            $user_id = Auth::id();
            Cart::instance('cart')->restore('userID_'.$user_id);
            $cart_items = Cart::content();

            $cart_count = $data['cart_count'] = Cart::count();
            
            if($cart_count!=0){
                $cart_count=0;
                foreach($cart_items as $key => $items)
                {
                    $product = Product::find($items->id);
                    
                    $cart_data[$cart_count]['uniqueId']      = $items->getUniqueId();
                    $cart_data[$cart_count]['product_id']    = $items->id;
                    $cart_data[$cart_count]['product_name']  = $items->name;
                    $cart_data[$cart_count]['product_image'] = $items->options['product_img'];
                    $cart_data[$cart_count]['price']         = $items->price;
                    $cart_data[$cart_count]['qty']           = $items->quantity;
                    $cart_data[$cart_count]['variant_id']    = $items->options['variant_id'];
                    $cart_data[$cart_count]['sku']           = $items->options['variant_sku'];
                    $cart_count++;
                }
            }
            $data['cart_data'] = $cart_data;
            $data['place_holder']=url('theme/images/products');
            return response()->json(['success'=> true,'data'=>$data]);
        }else{
            return response()->json(['success'=>false]);
        }
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
