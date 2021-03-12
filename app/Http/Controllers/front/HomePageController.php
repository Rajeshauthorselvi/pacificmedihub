<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Slider;
use App\Models\SliderBanner;
use App\Models\FeatureBlock;
use App\Models\FeatureBlockData;
use App\Models\Settings;
use Carbon\Carbon;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use DB;
use Auth;

class HomePageController extends Controller
{
    public function index($value='')
    {
        $data = array();

        $wishlist=[];
        if(Auth::check()){
            $user_id = Auth::id();
            Cart::instance('wishlist')->restore('userID_'.$user_id);
            $k = 1;
            foreach(Cart::instance('Wishlist')->content() as $item){
                $wishlist[$k]['product_id'] = $item->id;
                $wishlist[$k]['row_id']     = $item->getUniqueId();
                $k++;
            }
        }
        $data['wishlist'] = $wishlist;


        $setting = Settings::where('key','front-end')->pluck('content','code')->toArray();
        if(isset($setting['home'])){
            $statuses = unserialize($setting['home']);
        }

        $data['slider_status']         = isset($statuses['slider_status'])?$statuses['slider_status']:0;
        $data['features_status']       = isset($statuses['features_status'])?$statuses['features_status']:0;
        $data['new_arrival_status']    = isset($statuses['new_arrival_status'])?$statuses['new_arrival_status']:0;
        $data['category_block_status'] = isset($statuses['category_block_status'])?$statuses['category_block_status']:0;

    	$data['products'] = Product::where('published',1)->where('show_home',1)->where('is_deleted',0)
                                    ->orderBy('id','desc')->limit(10)->get();
        $slider = Slider::where('published',1)->where('is_deleted',0)->first();
        $data['banners']=array();
        if ($slider) {
            $data['banners'] = SliderBanner::where('slider_id',$slider->id)->orderBy('display_order','asc')->get();
        }

        $feature = FeatureBlock::where('published',1)->where('is_deleted',0)->first();
        $data['features']=array();
        if ($feature) {
            $data['features'] = FeatureBlockData::where('feature_id',$feature->id)->get();
        }

        //dd($data['features']);

        $categories = DB::table('categories as c')
                        ->select('c.name as category_name','c.search_engine_name as category_search_engine_name','p.*')
                        ->leftJoin('products as p','c.id','p.category_id')
                        ->where('c.published',1)->where('c.show_home',1)->where('c.is_deleted',0)
                        ->where('p.published',1)->where('p.show_home',1)->where('p.is_deleted',0)
                        ->orderBy('c.display_order','asc')->limit(10)->get();
        $category_products = [];
        foreach ($categories as $value) {
            $category_products[$value->category_name][] = $value;
        }
        $data['category_products'] = $category_products;

    	return view('front.home.home',$data);
    }

    public function search(Request $request)
    {
    	if($request->catgory_id==0){
        	$get_product = Product::where('name','like','%'.$request->search_text.'%')->where('published',1)->where('show_home',1)->where('is_deleted',0)->limit(10)->get();
    	}else{
        	$get_product = DB::table('categories as c')->leftJoin('products as p','c.id','p.category_id')
        				 ->where('c.id',$request->catgory_id)->orWhere('c.parent_category_id',$request->catgory_id)
        				 ->where('p.name','like','%'.$request->search_text.'%')->where('p.published',1)
        				 ->where('p.show_home',1)->where('p.is_deleted',0)->limit(10)->get();
        }
        $output=array();
        if(count($get_product)!=0){
	        foreach ($get_product as $key => $product) {
                $category_slug = isset($product->category->search_engine_name)?$product->category->search_engine_name:'categories';
                $product_id = base64_encode($product->id);
                $url = url($category_slug.'/'.$product->search_engine_name.'/'.$product_id);
	            $output[$key] = '<li><a href='.$url.'>'.$product->name.'</a></li>';
	        }
	    }else{
	    	$output = "<li><a href='javascript:void(0);'>No Result</a></li>";
	    }
        return response()->json($output);
    }
}
