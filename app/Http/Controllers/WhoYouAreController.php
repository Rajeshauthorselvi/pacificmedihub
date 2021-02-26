<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Redirect;
class WhoYouAreController extends Controller
{
    public function index()
    {
    	$data=array();
    	if (Auth::check() || Auth::guard('employee')->check()) {
    		return Redirect::route('admin.dashboard');
    	}
    	return view('who_you_are.login',$data);
    }
}
