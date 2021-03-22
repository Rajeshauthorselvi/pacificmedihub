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

class MyRFQController extends Controller
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
        $all_rfq_data = RFQ::where('customer_id',$user_id)->orderBy('id','desc')->get();
        
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
        $rfq = $data['rfq'] = RFQ::find($id);
        
        $user = User::find($rfq->customer_id);
        if(isset($rfq->delivery_address_id)&& $rfq->delivery_address_id!=null){
            $del_add_id = $rfq->delivery_address_id;
        }else{
            $del_add_id = $user->address_id;
        }
        $data['cus_email']        = $user->email;
        $data['delivery_address'] = UserAddress::find($del_add_id);
        $data['admin_address']    = UserCompanyDetails::where('customer_id',1)->first();
        
        $rfq_products = RFQProducts::with('product','variant')->where('rfq_id',$id)->orderBy('id','desc')->get();
        $rfq_data = $rfq_items = array();
        foreach ($rfq_products as $key => $item) {
            $rfq_items[$key]['product_name'] =  $item->product->name;
            $rfq_items[$key]['variant_sku'] = $item->variant->sku;
            $rfq_items[$key]['variant_option1'] = isset($item->variant->optionName1->option_name)?$item->variant->optionName1->option_name:null;
            $rfq_items[$key]['variant_option_value1'] = isset($item->variant->optionValue1->option_value)?$item->variant->optionValue1->option_value:null;
            $rfq_items[$key]['variant_option2'] = isset($item->variant->optionName2->option_name)?$item->variant->optionName2->option_name:null;
            $rfq_items[$key]['variant_option_value2'] = isset($item->variant->optionValue2->option_value)?$item->variant->optionValue2->option_value:null;
            $rfq_items[$key]['variant_option3'] = isset($item->variant->optionName3->option_name)?$item->variant->optionName3->option_name:null;
            $rfq_items[$key]['variant_option_value3'] = isset($item->variant->optionValue3->option_value)?$item->variant->optionValue3->option_value:null;
            $rfq_items[$key]['variant_option4'] = isset($item->variant->optionName4->option_name)?$item->variant->optionName4->option_name:null;
            $rfq_items[$key]['variant_option_value4'] = isset($item->variant->optionValue4->option_value)?$item->variant->optionValue4->option_value:null;
            $rfq_items[$key]['variant_option5'] = isset($item->variant->optionName5->option_name)?$item->variant->optionName5->option_name:null;
            $rfq_items[$key]['variant_option_value5'] = isset($item->variant->optionValue5->option_value)?$item->variant->optionValue5->option_value:null;
            $rfq_items[$key]['quantity'] = $item->quantity;
            $rfq_items[$key]['rfq_price'] = isset($item->rfq_price)?(float)$item->rfq_price:'0.00';
            $rfq_items[$key]['sub_total'] = isset($item->sub_total)?(float)$item->sub_total:'0.00';
        }

        $rfq_data['total']       = isset($rfq->total_amount)?(float)$rfq->total_amount:'0.00';
        $rfq_data['discount']    = isset($rfq->order_discount)?(float)$rfq->order_discount:'0.00';
        $rfq_data['tax']         = isset($rfq->order_tax_amount)?(float)$rfq->order_tax_amount:'0.00';
        $rfq_data['grand_total'] = isset($rfq->sgd_total_amount)?(float)$rfq->sgd_total_amount:'0.00';
        $rfq_data['notes']       = isset($rfq->notes)?$rfq->notes:'';

        $data['rfq_data']     = $rfq_data;
        $data['rfq_products'] = $rfq_items;
        //dd($data);
        return view('front/customer/rfq/rfq_view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }

        $user_id = Auth::id();
        $primary_id = Auth::user()->address_id;

        $id = base64_decode($id);
        $rfq = $data['rfq'] = RFQ::find($id);
        
        $user = User::find($rfq->customer_id);

        if(isset($rfq->delivery_address_id)&& $rfq->delivery_address_id!=null){
            $del_add_id = $rfq->delivery_address_id;
        }else{
            $del_add_id = $user->address_id;
        }

        if(isset($rfq->billing_address_id)&& $rfq->billing_address_id!=null){
            $bill_add_id = $rfq->billing_address_id;
        }else{
            $bill_add_id = $user->address_id;
        }

        $data['cus_email']   = $user->email;
        $data['delivery']    = UserAddress::find($del_add_id);
        $data['billing']     = UserAddress::find($bill_add_id);

        $data['all_address'] = UserAddress::where('customer_id',$user_id)->where('is_deleted',0)->get();

        $rfq_products = RFQProducts::with('product','variant')->where('rfq_id',$id)->orderBy('id','desc')->get();
        $rfq_data = $rfq_items = array();
        foreach ($rfq_products as $key => $item) {
            $rfq_items[$key]['rfq_items_id'] = $item->id;
            $rfq_items[$key]['rfq_id']       = $item->rfq_id;
            $rfq_items[$key]['product_name'] = $item->product->name;
            $rfq_items[$key]['variant_sku']  = $item->variant->sku;
            $rfq_items[$key]['variant_option1'] = isset($item->variant->optionName1->option_name)?$item->variant->optionName1->option_name:null;
            $rfq_items[$key]['variant_option_value1'] = isset($item->variant->optionValue1->option_value)?$item->variant->optionValue1->option_value:null;
            $rfq_items[$key]['variant_option2'] = isset($item->variant->optionName2->option_name)?$item->variant->optionName2->option_name:null;
            $rfq_items[$key]['variant_option_value2'] = isset($item->variant->optionValue2->option_value)?$item->variant->optionValue2->option_value:null;
            $rfq_items[$key]['variant_option3'] = isset($item->variant->optionName3->option_name)?$item->variant->optionName3->option_name:null;
            $rfq_items[$key]['variant_option_value3'] = isset($item->variant->optionValue3->option_value)?$item->variant->optionValue3->option_value:null;
            $rfq_items[$key]['variant_option4'] = isset($item->variant->optionName4->option_name)?$item->variant->optionName4->option_name:null;
            $rfq_items[$key]['variant_option_value4'] = isset($item->variant->optionValue4->option_value)?$item->variant->optionValue4->option_value:null;
            $rfq_items[$key]['variant_option5'] = isset($item->variant->optionName5->option_name)?$item->variant->optionName5->option_name:null;
            $rfq_items[$key]['variant_option_value5'] = isset($item->variant->optionValue5->option_value)?$item->variant->optionValue5->option_value:null;
            $rfq_items[$key]['quantity'] = $item->quantity;
            $rfq_items[$key]['rfq_price'] = isset($item->rfq_price)?(float)$item->rfq_price:'0.00';
            $rfq_items[$key]['sub_total'] = isset($item->sub_total)?(float)$item->sub_total:'0.00';
        }

        $rfq_data['total']       = isset($rfq->total_amount)?(float)$rfq->total_amount:'0.00';
        $rfq_data['discount']    = isset($rfq->order_discount)?(float)$rfq->order_discount:'0.00';
        $rfq_data['tax']         = isset($rfq->order_tax_amount)?(float)$rfq->order_tax_amount:'0.00';
        $rfq_data['grand_total'] = isset($rfq->sgd_total_amount)?(float)$rfq->sgd_total_amount:'0.00';
        $rfq_data['notes']       = isset($rfq->notes)?$rfq->notes:'';

        $data['rfq_data']     = $rfq_data;
        $data['rfq_products'] = $rfq_items;
        //dd($data);
        return view('front/customer/rfq/rfq_edit',$data);
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
    public function destroy(Request $request, $id)
    {
        dd($request->all(),$id);
        //RFQProducts::
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

    public function status()
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        return view('front/customer/rfq/rfq_success');
    }

    public function changeAddress(Request $request)
    {
        if($request->address_type==2){
            RFQ::where('id',$request->rfq_id)->update(['billing_address_id'=>$request->address_id]);
        }else if($request->address_type==1){
            RFQ::where('id',$request->rfq_id)->update(['delivery_address_id'=>$request->address_id]);
        }
        return response()->json(true);
    }
}
