<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function category($value='')
    {
    	return view('front.shop.category_product');
    }
    public function product($value='')
    {
    	return view('front.shop.single_product');
    }
}
