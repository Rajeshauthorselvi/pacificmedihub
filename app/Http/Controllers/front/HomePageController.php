<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;
use DB;

class HomePageController extends Controller
{
    public function index($value='')
    {
    	$data = array();
    	$data['categories'] = Categories::where('published',1)->where('is_deleted',0)->get();
    	$data['products'] = Product::where('published',1)->where('show_home',1)->where('is_deleted',0)->whereMonth('created_at',date('m'))->limit(10)->get();
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
	            $output[$key] = "<li><a href='javascript:void(0);'>".$product->name."</a></li>";
	        }
	    }else{
	    	$output = "<li><a href='javascript:void(0);'>No Result</a></li>";
	    }
        return response()->json($output);
    }
}
