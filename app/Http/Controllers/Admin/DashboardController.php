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
use App\Models\UserAddress;
use Carbon\Carbon;
use App\Models\CommissionValue;
use Auth;
use Redirect;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
    
        $data = array();
        $data['rfq_count'] = RFQ::where('status',22)->count();
        $data['delivery_completed'] = Orders::where('order_status',13)->count();
        $data['delivery_inprocess'] = Orders::where('order_status',15)->count();
        $data['pending_order_count'] = Orders::where('order_status',19)->count();
        $data['vendor_count'] = Vendor::where('is_deleted',0)->count();
        $data['customer_count'] = User::where('role_id',7)->where('status',1)->where('is_deleted',0)->count();
        $low_stock_count=Orders::LowStockQuantity();
        $data['low_stock_count']=$low_stock_count['low_stock_count'];

        $data['current_day_total']=Orders::where('order_status',13)
                                   ->whereDate('delivered_at',date('Y-m-d'))
                                   ->sum('sgd_total_amount');

        $data['current_week_total']=Orders::where('order_status',13)
                                   ->whereBetween('delivered_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                   ->sum('sgd_total_amount');

        $data['current_month_total']=Orders::where('order_status',13)
                                   ->whereMonth('delivered_at', date('m'))
                                   ->sum('sgd_total_amount');

        $data['current_year_total']=Orders::where('order_status',13)
                                   ->whereYear('delivered_at', date('Y'))
                                   ->sum('sgd_total_amount');




        
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

    public function getDeliveryAddress($customer_id)
    {
        $address = DB::table('address')
                     ->where('customer_id',$customer_id)
                     ->where('is_deleted',0)
                     ->pluck(DB::raw("CONCAT(name,', ',mobile,', ',address_line1,', ',post_code) as addres"),'id')
                     ->toArray();
        return $address;
    }
}
