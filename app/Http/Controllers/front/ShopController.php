<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Option;
use DB;

class ShopController extends Controller
{
    public function category($value='',$category_id)
    {
    	$id = base64_decode($category_id);
    	$category = Categories::where('id',$id)->first();

        $data['parent_id']      = isset($category->parent_category_id)?$category->parent_category_id:NULL;
        $data['selected_id']    = $id;
        if($id=='all'){
            $data['products']   = Product::where('published',1)->where('show_home',1)->where('is_deleted',0)
                                         ->paginate(10);
        }else{
            $data['products']   = Product::where('category_id',$id)->where('published',1)->where('show_home',1)
                                         ->where('is_deleted',0)->paginate(10);    
        }
    	$data['categories']     = Categories::where('published',1)->where('is_deleted',0)
    						 		    ->where('parent_category_id',NULL)->orderBy('display_order','asc')->get();
    	$data['image']          = isset($category->image)?$category->image:NULL;

    	return view('front.shop.category_product',$data);
    }
    public function product($category_slug='',$product_slug='',$product_id)
    {
        $id = base64_decode($product_id);
        $product = Product::with('product_images','sorted_product_image','product_variant')->where('id',$id)
                                  ->where('is_deleted',0)->first();

       $allowed_options=[]; 
       $default_variant_id=0; 
       
       
       $data['default_variant_id'] = 0;
       $data['options'] = null;
       $data['allowed_options'] = [];
       
        if($product->product_variant->count() > 0 ){
            foreach($product->product_variant as $key=> $variant){
                if($key=="0") {
                    $default_variant_id = $variant->id;
                    $default_sku = $variant->sku;
                }
                if(isset($allowed_options[$variant->option_id])){
                    array_push($allowed_options[$variant->option_id],$variant->option_value_id);
                }
                else $allowed_options[$variant->option_id] = [$variant->option_value_id];

                if(isset($allowed_options[$variant->option_id2])){
                    array_push($allowed_options[$variant->option_id2],$variant->option_value_id2);
                }
                else $allowed_options[$variant->option_id2] = [$variant->option_value_id2];

                if(isset($allowed_options[$variant->option_id3])){
                    array_push($allowed_options[$variant->option_id3],$variant->option_value_id3);
                }
                else $allowed_options[$variant->option_id3] = [$variant->option_value_id3];

                if(isset($allowed_options[$variant->option_id4])){
                    array_push($allowed_options[$variant->option_id4],$variant->option_value_id4);
                }
                else $allowed_options[$variant->option_id4] = [$variant->option_value_id4];

                if(isset($allowed_options[$variant->option_id5])){
                    array_push($allowed_options[$variant->option_id5],$variant->option_value_id5);
                }
                else $allowed_options[$variant->option_id5] = [$variant->option_value_id5];
            }
            
            $required_options = array_filter(array_keys($allowed_options));
            $data['options']  = [];
            if($required_options){
                $data['options'] = 
                Option::with(
                    ['getoptionvalues'=>function($q){$q->orderby('display_order');}]
                )->whereIn('id',$required_options)->orderBy(DB::raw('FIELD(id, '.implode(", " , $required_options).')'))->get();
            }
            $data['allowed_options'] = $allowed_options;  
            $data['default_variant_id'] = $default_variant_id;
            $data['default_sku'] = $default_sku;
        }
      
        $data['product'] = $product;

        session()->push('breadcrumbs','Products');
        //ddd($data);
    	return view('front.shop.single_product',$data);
    }
}
