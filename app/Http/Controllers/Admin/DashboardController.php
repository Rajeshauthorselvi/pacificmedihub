<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;

class DashboardController extends Controller
{
    public function index()
    {
    	return view('admin/dashboard');
    }

    public function getStateList(Request $request)
    {
    	$state = State::where('country_id',$request->country_id)->pluck("name","id");    
        return response()->json($state);
    }

    public function getCityList(Request $request)
    {
    	$state = City::where('state_id',$request->state_id)->pluck("name","id");    
        return response()->json($state);
    }
}
