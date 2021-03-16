<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Auth;

class WishlistController extends Controller
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
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $items = Cart::instance('Wishlist')->content();
            $wishlist_data = array();
            foreach($items as $key => $item)
            {
                $product = Product::find($item->id);
                $wishlist_data[$key]['uniqueId']      = $item->getUniqueId();
                $wishlist_data[$key]['product_id']    = $item->id;
                $wishlist_data[$key]['product_name']  = $item->name;
                $wishlist_data[$key]['product_image'] = $item->options['product_img'];

                $category_slug = $product->category->search_engine_name;
                $product_id = base64_encode($product->id);
                $url = url("$category_slug/$product->search_engine_name/$product_id");
                $wishlist_data[$key]['link'] = $url;
            }
            $data['user_id'] = $user_id;
            $data['count'] = Cart::count();
            $data['wishlist_data'] = $wishlist_data;
            
            return view('front/customer/wishlist',$data);
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
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
        
            $product_id  = base64_decode($request->product_id);
            $wish_action = $request->row_id;
            $product = Product::find($product_id);
            
            if(Cart::has($wish_action)){
                Cart::instance('wishlist')->remove($request->row_id);
                Cart::instance('wishlist')->store('userID_'.$user_id);
                $message="removed";
            }else{
                Cart::instance('wishlist')->add($product->id,$product->name,0,1,['product_img'=>$product->main_image]);
                Cart::instance('wishlist')->store('userID_'.$user_id);
                $message="added";
            }
            return response()->json($message);
        }else{
            return response()->json(false);
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
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
        }
       
        Cart::instance('wishlist')->remove($id);
        if($user_id > 0) Cart::instance('wishlist')->store('userID_'.$user_id);

        return redirect()->route('wishlist.index')->with('error', 'Item was removed from your wishlist!');
    }
}
