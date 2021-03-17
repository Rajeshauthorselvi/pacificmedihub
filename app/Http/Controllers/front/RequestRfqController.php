<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\UserAddress;
use App\Models\UserCompanyDetails;
use App\Models\Countries;
use App\Models\RFQ;
use App\Models\RFQProducts;
use App\Models\Prefix;
use App\Models\ProductVariantVendor;
use App\User;
use Auth;
use Str;
use Session;
use Redirect;
use DB;

class RequestRfqController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $data = array();
        $user_id = Auth::id();
        $all_rfq_data = RFQ::where('user_id',$user_id)->orderBy('id','desc')->get();
        $rfq_data = array();
        foreach ($all_rfq_data as $key => $rfq) {
            $item_count  = RFQProducts::where('rfq_id',$rfq->id)->count();
            $toatl_qty   = RFQProducts::where('rfq_id',$rfq->id)->sum('quantity');
            $rfq_data[$key]['id'] = $rfq->id;
            $rfq_data[$key]['create_date'] = date('d/m/Y',strtotime($rfq->created_at));
            $rfq_data[$key]['status'] = $rfq->statusName->status_name;
            $rfq_data[$key]['code'] = $rfq->order_no;
            $rfq_data[$key]['item_count'] = $item_count;
            $rfq_data[$key]['toatl_qty'] = $toatl_qty;
        }
        $data['rfq_datas'] = $rfq_data;
        return view('front/customer/rfq/rfq_index',$data);
    }

    public function show($rfq_id)
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $id = base64_decode($rfq_id);
        $data['admin_address'] = UserCompanyDetails::where('customer_id',1)->first();
        $data['rfq'] = RFQ::find($id);
        //dd($data);
        return view('front/customer/rfq/rfq_view',$data);
    }

    public function request()
    {
        if(Auth::check()){
        	$user_id    = Auth::id();
        	$primary_id = Auth::user()->address_id;

            $data['all_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('address_type',[1,2])
                                              ->where('is_deleted',0)->get();

            $delivery   = UserAddress::where('address_type',1)->where('customer_id',$user_id)->first();
            $primary    = UserAddress::where('id',$primary_id)->where('customer_id',$user_id)->first();

            if(isset($delivery)){
                $data['delivery'] = $delivery;
                $remove_id        = $delivery->id;
            }else{
                $data['delivery'] = $primary;
                $remove_id        = $primary_id;
            }

            $data['billing_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('id',[$remove_id])
                                                  ->where('is_deleted',0)->get();

            $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();

            $cart_data = [];
            Cart::instance('cart')->restore('userID_'.$user_id);
            $cart_items = Cart::content();
            foreach($cart_items as $key => $items)
            {
                $cart_data[$key]['uniqueId']      = $items->getUniqueId();
                $cart_data[$key]['product_id']    = $items->id;
                $cart_data[$key]['product_name']  = $items->name;
                $cart_data[$key]['product_image'] = $items->options['product_img'];
                $cart_data[$key]['qty']           = $items->quantity;
                $cart_data[$key]['variant_id']    = $items->options['variant_id'];
            }
            $data['user_id']    = $user_id;
            $data['cart_count'] = Cart::count();
            $data['cart_data']  = $cart_data;
            return view('front/customer/rfq/proceed_rfq',$data);
        }else{
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
    }

    public function store(Request $request)
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }

        $rfq_code = Prefix::where('key','prefix')->where('code','rfq')->value('content');
        if (isset($rfq_code)) {
            $value             = unserialize($rfq_code);
            $char_val          = $value['value'];
            $year              = date('Y');
            $total_datas       = RFQ::count();
            $total_datas_count = $total_datas+1;

            if(strlen($total_datas_count)==1){
                $start_number = '0000'.$total_datas_count;
            }else if(strlen($total_datas_count)==2){
                $start_number = '000'.$total_datas_count;
            }else if(strlen($total_datas_count)==3){
                $start_number = '00'.$total_datas_count;
            }else if(strlen($total_datas_count)==4){
                $start_number = '0'.$total_datas_count;
            }else{
                $start_number = $total_datas_count;
            }
            $replace_year   = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
            $rfq_code = $replace_number;
        }

        $user_id = Auth::id();
        $sales_rep_id = UserCompanyDetails::where('customer_id',$user_id)->value('sales_rep');

        $rfq_details=[
            'order_no'            => $rfq_code,
            'status'              => 1,
            'customer_id'         => $user_id,
            'sales_rep_id'        => isset($sales_rep_id)?$sales_rep_id:0,
            'notes'               => $request->notes,
            'user_id'             => $user_id,
            'delivery_address_id' => $request->delivery_address,
            'billing_address_id'  => $request->billing_address,
            'created_user_type'   => 3,
            'created_at'          => date('Y-m-d H:i:s')
        ];

        $rfq_id = RFQ::insertGetId($rfq_details);

        $item_data = [];
        Cart::instance('cart')->restore('userID_'.$user_id);
        $cart_items = Cart::content();
        foreach($cart_items as $key => $items)
        {
            $item_data[$key]['uniqueId']      = $items->getUniqueId();
            $item_data[$key]['product_id']    = $items->id;
            $item_data[$key]['qty']           = $items->quantity;
            $item_data[$key]['variant_id']    = $items->options['variant_id'];
        }
        
        foreach($item_data as $item){
            $prices = ProductVariantVendor::where('product_variant_id',$item['variant_id'])->first();
            $rfq_items =[
                'rfq_id'                    => $rfq_id,
                'product_id'                => $item['product_id'],
                'product_variant_id'        => $item['variant_id'],
                'base_price'                => $prices->base_price,
                'retail_price'              => $prices->retail_price,
                'minimum_selling_price'     => $prices->minimum_selling_price,
                'quantity'                  => $item['qty'],
                'sub_total'                 => 0
            ];
            RFQProducts::insert($rfq_items);

            Cart::instance('cart')->remove($item['uniqueId']);
            Cart::instance('cart')->store('userID_'.$user_id);
        }

        $added_rfq_data = RFQ::find($rfq_id);
        $data = $added_rfq_data->order_no;
        return redirect()->route('rfq.status')->with('message',$data);
    }

    public function status()
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        return view('front/customer/rfq/rfq_success');
    }
}
