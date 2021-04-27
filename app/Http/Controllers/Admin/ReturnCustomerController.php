<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerOrderReturn;
use App\Models\CustomerOrderReturnProducts;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Options;
use App\Models\OptionValues;
use Redirect;
use DB;
use Auth;
class ReturnCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $data = $returns = array();
      $order_returns = CustomerOrderReturn::where('order_return_status',2)->orderBy('id','desc')->get();
      foreach ($order_returns as $value) {
        $order = Orders::find($value->order_id);
        $order_products_qty = OrderProducts::where('order_id',$order->id)->sum('quantity');
        $return_products_recived_qty = CustomerOrderReturnProducts::where('customer_order_return_id',$value->id)->where('order_id',$value->order_id)->sum('qty_received');
        $return_products_return_qty = CustomerOrderReturnProducts::where('customer_order_return_id',$value->id)->where('order_id',$value->order_id)->sum('return_quantity');
        $returns[]=[
          'return_id'       => $value->id,
          'return_date'     => date('d-m-Y',strtotime($value->created_at)),
          'customer'        => $value->customer->name,
          'order_number'    => $order->order_no,
          'quantity'        => $order_products_qty,
          'return_quantity' => $return_products_return_qty,
          //'qty_received'    => $return_products_recived_qty,
          'status_id'       => $value->statusName->id,
          'status'          => $value->statusName->status_name,
          'color_code'      => $value->statusName->color_codes
        ];
      }
      $data['returns'] = $returns;
      return view('admin.stock.stock-in-transit-customer.index',$data);
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
        //
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
