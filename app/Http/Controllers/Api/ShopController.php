<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SliderBanner;
use App\Models\Settings;
use Carbon\Carbon;
use App\Models\Notification;
use App\Models\ProductVariantVendor;
use App\Models\Option;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use DB;
use Auth;

class ShopController extends Controller
{
    public function home()
    {	
		$data = array();
        $data['user_id'] = null;
        
        if(Auth::check()){
        	$data['user_id'] = Auth::id();
        }

        $setting = Settings::where('key','front-end')->pluck('content','code')->toArray();
        if(isset($setting['home'])){
            $statuses = unserialize($setting['home']);
        }

        $data['slider_status']         = isset($statuses['slider_status'])?$statuses['slider_status']:0;
        $data['features_status']       = isset($statuses['features_status'])?$statuses['features_status']:0;
        $data['new_arrival_status']    = isset($statuses['new_arrival_status'])?$statuses['new_arrival_status']:0;

        $slider = Slider::where('published',1)->where('is_deleted',0)->first();
        $data['banners']=array();
        if ($slider) {
            $data['banners'] = SliderBanner::select('id','title','images','display_order')->where('slider_id',$slider->id)
            							   ->orderBy('display_order','asc')->get();
        }

        $data['categories'] = Categories::select('id','name','icon','display_order')->where('published',1)
        								->where('is_deleted',0)->where('show_home',1)->where('parent_category_id',NULL)
										->limit(6)->orderBy('display_order','asc')->get();

    	$data['new_arrival_products'] = Product::select('id','name','code','main_image')->where('published',1)
    										   ->where('show_home',1)->where('is_deleted',0)
                                    		   ->orderBy('id','desc')->limit(10)->get();
        
        $data['banner_image_url']    = url('theme/images/sliders/');
        $data['category_image_url']  = url('theme/images/categories/icons/');
        $data['product_image_url']   = url('theme/images/products/main/');
        $data['dummy_image']	     = url('theme/images/products/placeholder.jpg');
        return response()->json($data);
    }

    public function category($id)
    {
        $data = array();
        $data['user_id'] = null;
        if(Auth::check()){
            $data['user_id'] = Auth::id();
        }

    	$category = Categories::where('id',$id)->first();
        $data['parent_id']      = isset($category->parent_category_id)?$category->parent_category_id:NULL;
        if($id=='all'){
            $data['products']   = Product::select('id','name','code','main_image')->where('published',1)
            							 ->where('is_deleted',0)->paginate(10);
        }else{
            $check_parent       = Categories::find($id);
            if($check_parent->parent_category_id){
                $products       = Product::select('id','name','code','main_image')->where('category_id',$id)
                						 ->where('published',1)->where('is_deleted',0)->paginate(10);    
            }else{
                $category_ids   = Categories::where('id',$id)->orWhere('parent_category_id',$id)->where('published',1)
                                          ->where('is_deleted',0)->pluck('id')->toArray();

                $products       = Product::select('id','name','code','main_image')->whereIn('category_id',$category_ids)
                						 ->where('published',1)->where('is_deleted',0)->paginate(10);    
            }
            $data['products']   = $products;
        }

    	$data['product_image_url']   = url('theme/images/products/main/');
        $data['dummy_image']	     = url('theme/images/products/placeholder.jpg');

        return response()->json($data);
    }


    public function product($id)
    {   
        $data = array();

        $data['user_id'] = null;
        if(Auth::check()){
            $data['user_id'] = Auth::id();
        }

        $product = Product::with('product_images','product_variant')
                          ->where('id',$id)->where('is_deleted',0)->first();

       $allowed_options=[]; 
       $default_variant_id=0; 
       
       
       $data['default_variant_id'] = 0;
       $data['default_sku'] = null;
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
                    $allowed_options[$variant->option_id][] = ['variant_id'=>$variant->id,'sku'=>$get_sku->sku,'option_value_id'=>$variant->option_value_id];
                }
                if($variant->option_id2){
                    $allowed_options[$variant->option_id2][] = ['variant_id'=>$variant->id,'sku'=>$get_sku->sku,'option_value_id'=>$variant->option_value_id2];
                }
                if($variant->option_id3){
                    $allowed_options[$variant->option_id3][] = ['variant_id'=>$variant->id,'sku'=>$get_sku->sku,'option_value_id'=>$variant->option_value_id3];
                }
                if($variant->option_id4){
                    $allowed_options[$variant->option_id4][] = ['variant_id'=>$variant->id,'sku'=>$get_sku->sku,'option_value_id'=>$variant->option_value_id4];
                }
                if($variant->option_id5){
                    $allowed_options[$variant->option_id5][] = ['variant_id'=>$variant->id,'sku'=>$get_sku->sku,'option_value_id'=>$variant->option_value_id5];
                }
            }
            
            $required_options = array_filter(array_keys($allowed_options));
            
            $data['options']  = [];
            if($required_options){
                $data['options'] = Option::select('id','option_name')->with([
                    'getoptionvalues'=>function($q){
                        $q->select('id','option_id','option_value')->orderby('display_order','asc');
                    }
                ]
                )->whereIn('id',$required_options)->orderBy(DB::raw('FIELD(id, '.implode(", " , $required_options).')'))->get();
            }
            $data['allowed_options'] = $allowed_options;  
            $data['default_variant_id'] = $default_variant_id;
            $data['default_sku'] = $default_sku;
        }
        
        $data['product'] = $product;

    	return response()->json($data);
    }

    public function getCounts()
    {
    	$wish_list = [];
        $cart_count = 0;
        $notify_count = 0;
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $k = 1;
            $wishlist = Cart::instance('Wishlist')->content();
            foreach($wishlist as $item){
                $wish_list[$k]['product_id'] = $item->id;
                $k++;
            }
            $data['user_id'] = $user_id;
            Cart::instance('cart')->restore('userID_'.$user_id);
            $cart_count = Cart::count();

            $notify_count = Notification::where('customer_id',$user_id)->where('if_read','no')->count();
        }
		$data['cart_count']   = $cart_count;
        $data['notify_count'] = $notify_count;
        $data['wishlist']     = $wish_list;

        return response()->json($data);
    }
}
