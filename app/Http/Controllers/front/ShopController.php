<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductVariantVendor;
use App\Models\Option;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use DB;
use Auth;

class ShopController extends Controller
{
    public function category($value='',$category_id)
    {
        $data = array();

        $wishlist=[];
        $data['user_id'] = null;
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $k = 1;
            foreach(Cart::instance('Wishlist')->content() as $item){
                $wishlist[$k]['product_id'] = $item->id;
                $wishlist[$k]['row_id']     = $item->getUniqueId();
                $k++;
            }
            $data['user_id'] = $user_id;
        }
        $data['wishlist'] = $wishlist;

    	$id = base64_decode($category_id);
    	$category = Categories::where('id',$id)->first();

        $data['parent_id']      = isset($category->parent_category_id)?$category->parent_category_id:NULL;
        $data['selected_id']    = $id;
        if($id=='all'){
            $data['products']   = Product::where('published',1)->where('is_deleted',0)->paginate(10);
        }else{
            $check_parent       = Categories::find($id);
            if($check_parent->parent_category_id){
                $products       = Product::where('category_id',$id)->where('published',1)
                                         ->where('is_deleted',0)->paginate(10);    
            }else{
                $category_ids   = Categories::where('id',$id)->orWhere('parent_category_id',$id)->where('published',1)
                                          ->where('is_deleted',0)->pluck('id')->toArray();

                $products       = Product::whereIn('category_id',$category_ids)->where('published',1)
                                         ->where('is_deleted',0)->paginate(10);    
            }
            $data['products']   = $products;
        }
    	$data['categories']     = Categories::where('published',1)->where('is_deleted',0)
    						 		    ->where('parent_category_id',NULL)->orderBy('display_order','asc')->get();
    	$data['image']          = isset($category->image)?$category->image:NULL;

    	return view('front.shop.category_product',$data);
    }
    
    public function product($category_slug='',$product_slug='',$product_id)
    {   
        $data = array();

        $wishlist=[];
        $data['user_id'] = null;
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $k = 1;
            foreach(Cart::instance('Wishlist')->content() as $item){
                $wishlist[$k]['product_id'] = $item->id;
                $wishlist[$k]['row_id']     = $item->getUniqueId();
                $k++;
            }
            $data['user_id'] = $user_id;
        }
        $data['wishlist'] = $wishlist;


        $id = base64_decode($product_id);
        $product = Product::with('product_images','product_variant')
                          ->where('id',$id)->where('is_deleted',0)->first();

       $allowed_options=[]; 
       $default_variant_id=0; 
       
       
       $data['default_variant_id'] = 0;
       $data['options'] = null;
       $data['allowed_options'] = [];

        if(isset($product->product_variant) && ($product->product_variant->count()!=0 )){

            foreach($product->product_variant as $key=> $variant){


                $get_sku = DB::table('product_variant_vendors')
                                    ->where('product_id',$variant->product_id)
                                    ->where('product_variant_id',$variant->id)
                                    ->orderBy('retail_price','desc')
                                    ->first();
                
                if($key=="0") {
                    $default_variant_id = $variant->id;
                    $default_sku = $get_sku->sku;
                }

                if($variant->option_id){
                    $allowed_options[$variant->option_id][] = ['id'=>$variant->id,'sku'=>$get_sku->sku,'value'=>$variant->option_value_id];
                }
                if($variant->option_id2){
                    $allowed_options[$variant->option_id2][] = ['id'=>$variant->id,'sku'=>$get_sku->sku,'value'=>$variant->option_value_id2];
                }
                if($variant->option_id3){
                    $allowed_options[$variant->option_id3][] = ['id'=>$variant->id,'sku'=>$get_sku->sku,'value'=>$variant->option_value_id3];
                }
                if($variant->option_id4){
                    $allowed_options[$variant->option_id4][] = ['id'=>$variant->id,'sku'=>$get_sku->sku,'value'=>$variant->option_value_id4];
                }
                if($variant->option_id5){
                    $allowed_options[$variant->option_id5][] = ['id'=>$variant->id,'sku'=>$get_sku->sku,'value'=>$variant->option_value_id5];
                }
            }
            
            $required_options = array_filter(array_keys($allowed_options));
            
            $data['options']  = [];
            if($required_options){
                $data['options'] = Option::with([
                    'getoptionvalues'=>function($q){
                        $q->orderby('display_order');
                    }
                ]
                )->whereIn('id',$required_options)->orderBy(DB::raw('FIELD(id, '.implode(", " , $required_options).')'))->get();
            }
            $data['allowed_options'] = $allowed_options;  
            $data['default_variant_id'] = $default_variant_id;
            $data['default_sku'] = $default_sku;
        }
        $data['related_products'] = Product::with('product_images','product_variant')->where('category_id',$product->category_id)->whereNotIn('id',[$product->id])->where('is_deleted',0)->orderBy('id','desc')->get();
        $data['product'] = $product;
    	return view('front.shop.single_product',$data);
    }
}
