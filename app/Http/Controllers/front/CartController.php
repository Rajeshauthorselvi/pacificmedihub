<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use App\Models\Product;
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
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('cart')->restore('userID_'.$user_id);
        }
       return view('front/customer/cart');
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
        $price = (float)$request->price;
        $qty = (int)$request->qty_count;
        
        $product = Product::find($request->product_id);
        
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('cart')->restore('userID_'.$user_id);
        }

        if(isset($user_id)){
            Cart::instance('cart')->add($product->id, $product->name, $price, $qty,[
                'product_variant_id'=>$request->variant_id
            ]);
            Cart::store('userID_'.$user_id);
        }
        else{
            $cartItem = Cart::add($product->id, $product->name, $price, $qty,['product_variant_id'=>$request->variant_id]);
        }
        return redirect()->route('cart.index')->with('success_message', 'Item was added to your cart!');
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
