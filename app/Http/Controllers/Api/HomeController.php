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
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use DB;
use Auth;

class HomeController extends Controller
{
    public function index()
    {	
		$data = array();

        $data['user_id'] = null;
        $data['token']   = null;
        $wishlist = [];
        $cart_count = 0;
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
            Cart::instance('cart')->restore('userID_'.$user_id);
            $cart_count = Cart::count();
        }
		$data['cart_count'] = $cart_count;
        $data['wishlist'] = $wishlist;


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
}
