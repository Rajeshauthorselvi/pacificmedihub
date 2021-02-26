<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Product;

class HomePageController extends Controller
{
    public function index($value='')
    {
    	$data = array();
    	$data['categories'] = Categories::where('published',1)->where('is_deleted',0)->get();
    	$data['products'] = Product::where('published',1)->where('show_home',1)->where('is_deleted',0)->whereMonth('created_at',date('m'))->limit(10)->get();
    	//dd($data);
    	return view('front.home.home',$data);
    }
}
