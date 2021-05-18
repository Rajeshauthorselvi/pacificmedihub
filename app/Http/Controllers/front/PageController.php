<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;

class PageController extends Controller
{
    public function index($slug='',$page_id)
    {
    	$data = array();
    	$id = base64_decode($page_id);
    	$data['page'] = StaticPage::find($id);
    	return view('front.static-page.page',$data);
    }
}
