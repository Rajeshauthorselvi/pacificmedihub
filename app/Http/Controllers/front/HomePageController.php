<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;

class HomePageController extends Controller
{
    public function index($value='')
    {
    	$data = array();
    	$data['categories'] = Categories::where('published',1)->where('is_deleted',0)->get();
    	return view('front.home.home',$data);
    }
}
