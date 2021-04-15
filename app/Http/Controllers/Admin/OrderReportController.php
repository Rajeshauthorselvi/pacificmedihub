<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderStatus;
use App\User;
use DB;
class OrderReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data['all_customers']=['Please Select']+User::where('role_id',7)
                               ->where('status',1)
                               ->where('is_deleted',0)
                               ->pluck('name','id')
                               ->toArray();

        $data['order_status']=['Please Select']+OrderStatus::pluck('status_name','id')->toArray();
        $data['all_orders']=Orders::get();

        return view('admin.report.order.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data['all_customers']=['Please Select']+User::where('role_id',7)
                               ->where('status',1)
                               ->where('is_deleted',0)
                               ->pluck('name','id')
                               ->toArray();
        $data['order_status']=['Please Select']+OrderStatus::pluck('status_name','id')->toArray();

        $orders=Orders::where('order_status','<>',"");
                if ($request->get('filter_date')!=null) {
                    $orders->whereDate('created_at',date('Y-m-d',strtotime($request->get('filter_date'))));
                }
                if ($request->get('customer_id')!=null) {
                    $orders->where('customer_id',$request->get('customer_id'));
                }
                if ($request->get('filter_value')!=null) {
                    $orders->where('exchange_total_amount',$request->get('filter_value'));
                }
                if ($request->get('filter_status')!="0") {
                    $orders->where('order_status',$request->get('filter_status'));
                }
                $orders=$orders->get();
                $data['all_orders']=$orders;

        return view('admin.report.order.index',$data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
