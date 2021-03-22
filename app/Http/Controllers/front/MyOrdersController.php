<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\UserAddress;
use App\Models\UserCompanyDetails;
use App\Models\Countries;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\PaymentHistory;
use App\User;
use Auth;
use Str;
use Session;
use Redirect;
use DB;

class MyOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $data = array();
        $user_id = Auth::id();
        $all_data = Orders::where('customer_id',$user_id)->orderBy('id','desc')->get();
        
        $order_data = array();
        foreach ($all_data as $key => $item) {
            $item_count  = OrderProducts::where('order_id',$item->id)->count();
            $toatl_qty   = OrderProducts::where('order_id',$item->id)->sum('quantity');
            $order_data[$key]['id'] = $item->id;
            $order_data[$key]['create_date']  = date('d/m/Y',strtotime($item->created_at));
            $order_data[$key]['delivered_at'] = $item->delivered_at;
            $order_data[$key]['status']       = $item->statusName->status_name;
            $order_data[$key]['code']         = $item->order_no;
            $order_data[$key]['item_count']   = $item_count;
            $order_data[$key]['toatl_qty']    = $toatl_qty;
        }
        $data['order_data'] = $order_data;
        //dd($data);
        return view('front/customer/orders/order_index',$data);
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
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $id = base64_decode($id);
        $order = $data['order'] = Orders::find($id);
        
        $user = User::find($order->customer_id);
        if(isset($order->delivery_address_id)&& $order->delivery_address_id!=null){
            $del_add_id = $order->delivery_address_id;
        }else{
            $del_add_id = $user->address_id;
        }
        $data['cus_email']        = $user->email;
        $data['delivery_address'] = UserAddress::find($del_add_id);
        $data['admin_address']    = UserCompanyDetails::where('customer_id',1)->first();
        
        $order_products = OrderProducts::with('product','variant')->where('order_id',$id)->orderBy('id','desc')->get();
        $order_data = $order_items = array();
        foreach ($order_products as $key => $item) {
            $order_items[$key]['product_name'] =  $item->product->name;
            $order_items[$key]['variant_sku'] = $item->variant->sku;
            $order_items[$key]['variant_option1'] = isset($item->variant->optionName1->option_name)?$item->variant->optionName1->option_name:null;
            $order_items[$key]['variant_option_value1'] = isset($item->variant->optionValue1->option_value)?$item->variant->optionValue1->option_value:null;
            $order_items[$key]['variant_option2'] = isset($item->variant->optionName2->option_name)?$item->variant->optionName2->option_name:null;
            $order_items[$key]['variant_option_value2'] = isset($item->variant->optionValue2->option_value)?$item->variant->optionValue2->option_value:null;
            $order_items[$key]['variant_option3'] = isset($item->variant->optionName3->option_name)?$item->variant->optionName3->option_name:null;
            $order_items[$key]['variant_option_value3'] = isset($item->variant->optionValue3->option_value)?$item->variant->optionValue3->option_value:null;
            $order_items[$key]['variant_option4'] = isset($item->variant->optionName4->option_name)?$item->variant->optionName4->option_name:null;
            $order_items[$key]['variant_option_value4'] = isset($item->variant->optionValue4->option_value)?$item->variant->optionValue4->option_value:null;
            $order_items[$key]['variant_option5'] = isset($item->variant->optionName5->option_name)?$item->variant->optionName5->option_name:null;
            $order_items[$key]['variant_option_value5'] = isset($item->variant->optionValue5->option_value)?$item->variant->optionValue5->option_value:null;
            $order_items[$key]['quantity'] = $item->quantity;
            $order_items[$key]['final_price'] = isset($item->final_price)?(float)$item->final_price:'0.00';
            $order_items[$key]['sub_total'] = isset($item->sub_total)?(float)$item->sub_total:'0.00';
        }

        $paid_amount = PaymentHistory::where('ref_id',$id)->where('payment_from',2)->sum('amount');
        
        $order_data['total']       = isset($order->total_amount)?(float)$order->total_amount:'0.00';
        $order_data['discount']    = isset($order->order_discount)?(float)$order->order_discount:'0.00';
        $order_data['tax']         = isset($order->order_tax_amount)?(float)$order->order_tax_amount:'0.00';
        $order_data['grand_total'] = isset($order->sgd_total_amount)?(float)$order->sgd_total_amount:'0.00';

        $order_data['paid_amount'] = (float)$paid_amount;
        $order_data['due_amount']  = (float)$order->sgd_total_amount - (float)$paid_amount;

        $data['order_data']     = $order_data;
        $data['order_products'] = $order_items;
        //dd($data);
        return view('front/customer/orders/order_view',$data);
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
