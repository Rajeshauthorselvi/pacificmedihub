<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhoYouAreController extends Controller
{
    public function index()
    {
    	$data=array();
    	return view('who_you_are.login',$data);
    }
}
