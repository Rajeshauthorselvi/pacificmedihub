<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\City;
use App\Models\RFQ;
use App\Models\Orders;
use App\Models\Vendor;
use App\User;
use Carbon\Carbon;
use App\Models\CommissionValue;

class DashboardController extends Controller
{
    public function index()
    {
        $data = array();
        $data['rfq_count'] = RFQ::where('status',1)->count();
        $data['orders_count'] = Orders::where('created_at', '>', Carbon::now()->startOfWeek())
                                    ->where('created_at', '<', Carbon::now()->endOfWeek())
                                    ->count();
        $data['pending_order_count'] = Orders::where('order_status',1)->count();
        $data['vendor_count'] = Vendor::where('is_deleted',0)->count();
        $data['customer_count'] = User::where('role_id',7)->where('is_deleted',0)->count();
        //dd($data);
    	return view('admin/dashboard',$data);
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

    public function commissionValue(Request $request)
    {
        $commission = CommissionValue::find($request->id);
        $value = $commission->commission_value;
        return $value;
    }

    public function errorPage()
    {
        return view('error');
    }
}
