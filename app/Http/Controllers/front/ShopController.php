<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;

class ShopController extends Controller
{
    public function category($value='',$category_id)
    {
    	$id = base64_decode($category_id);
    	$category = Categories::where('id',$id)->first();
    	$data['categories'] = Categories::where('published',1)->where('is_deleted',0)
    						 		    ->where('parent_category_id',NULL)->orderBy('display_order','asc')->get();
    	$data['image']      = isset($category->image)?$category->image:NULL;
    	$data['products']	= Product::where('category_id',$id)->where('published',1)->where('is_deleted',0)
    								 ->paginate(10);
    	return view('front.shop.category_product',$data);
    }
    public function product($value='')
    {
    	return view('front.shop.single_product');
    }
}
