<?php

namespace App\Http\Controllers\front;

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
        if(Auth::check()){

            $wishlist=[];
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $k = 1;
            foreach(Cart::instance('Wishlist')->content() as $item){
                $wishlist[$k]['product_id'] = $item->id;
                $wishlist[$k]['row_id']     = $item->getUniqueId();
                $k++;
            }
            $data['wishlist'] = $wishlist;

            $cart_data = [];
            $user_id = Auth::id();
            Cart::instance('cart')->restore('userID_'.$user_id);
            $cart_items = Cart::content();

            $cart_count = $data['cart_count'] = Cart::count();
            
            if($cart_count!=0){
                foreach($cart_items as $key => $items)
                {
                    $product = Product::find($items->id);
                    
                    $cart_data[$key]['uniqueId']      = $items->getUniqueId();
                    $cart_data[$key]['product_id']    = $items->id;
                    $cart_data[$key]['product_name']  = $items->name;
                    $cart_data[$key]['product_image'] = $items->options['product_img'];
                    $cart_data[$key]['price']         = $items->price;
                    $cart_data[$key]['qty']           = $items->quantity;
                    $cart_data[$key]['variant_id']    = $items->options['variant_id'];
                    $cart_data[$key]['sku']           = $items->options['variant_sku'];

                    $category_slug = $product->category->search_engine_name;
                    $product_id = base64_encode($product->id);
                    $url = url("$category_slug/$product->search_engine_name/$product_id");
                    $cart_data[$key]['link'] = $url;
                }
            }
            $data['cart_data'] = $cart_data;
            return view('front/customer/cart',$data);
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
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }

        $price = (float)$request->price;
        $qty = (int)$request->qty_count;
        
        $product = Product::find($request->product_id);    
    
        $user_id = Auth::id();
        Cart::instance('cart')->restore('userID_'.$user_id);
        
        if(isset($user_id)){
            Cart::instance('cart')->add($product->id, $product->name, $price, $qty,[
                'product_img' => $request->product_img,
                'variant_id'  => $request->variant_id,
                'variant_sku' => $request->variant_sku
            ]);
            Cart::store('userID_'.$user_id);
        }
        else{
            $cartItem = Cart::add($product->id, $product->name, $price, $qty,[
                'product_img' => $request->product_img,
                'variant_id'  => $request->variant_id,
                'variant_sku' => $request->variant_sku
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'Item was added to your cart!');
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
        $product_variant = ProductVariant::find($request->variant_id);
        $product = Product::where('id',$product_variant->product_id)->first();

        $price = (float)0.00;
        $qty = (int)$request->qty_count;


        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('cart')->restore('userID_'.$user_id);
        }

        if(Cart::has($request->cart_id)){
            Cart::instance('cart')->remove($request->cart_id);
            Cart::instance('cart')->store('userID_'.$user_id);
        }

        if(isset($user_id)){
            $cart = Cart::instance('cart')->add($product->id, $product->name, $price, $qty,[
                'product_img' => $product->main_image,
                'variant_id'  => $request->variant_id,
                'variant_sku' => $product_variant->sku
            ]);
            Cart::store('userID_'.$user_id);
        }

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('cart')->restore('userID_'.$user_id);
        }
       
        Cart::instance('cart')->remove($id);
        if($user_id > 0) Cart::instance('cart')->store('userID_'.$user_id);

        return redirect()->route('cart.index')->with('error', 'Item was removed from your cart!');
    }
}
