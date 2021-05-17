<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RFQ;
use App\Models\RFQProducts;
use App\Models\UserAddress;
use App\Models\DeliveryMethod;
use App\Models\Product;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use App\Models\Prefix;
use App\Models\ProductVariantVendor;
use App\Models\Notification;
use App\Models\OrderStatus;
use App\User;
use Auth;
use DB;
use Validator;
use Melihovv\ShoppingCart\Facades\ShoppingCart as Cart;
class RFQApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
            $user_id    = Auth::id();
            $primary_id = Auth::user()->address_id;

            /*$data['all_address'] = UserAddress::where('customer_id',$user_id)->whereNotIn('address_type',[1,2])
                                              ->where('is_deleted',0)->get();*/

            $delivery   = UserAddress::where('address_type',0)->where('customer_id',$user_id)->first();
            $primary    = UserAddress::where('id',$primary_id)->where('customer_id',$user_id)->first();

            if(isset($delivery)){
                $default_shipping_address = $delivery;
                $remove_id        = $delivery->id;
            }else{
                $default_shipping_address = $primary;
                $remove_id        = $primary_id;
            }
            $data['shipping_address']=$default_shipping_address;
            $data['billing_address']=$default_shipping_address;


            /*$data['billing_address'] = UserAddress::where('customer_id',$user_id)
                                       ->whereNotIn('id',[$remove_id])
                                       ->where('is_deleted',0)->get();*/
         /*   $data['all_address'] = UserAddress::where('customer_id',$user_id)
                                       ->where('is_deleted',0)
                                       ->get();*/

            // $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
            $data['delivery_method'] = DeliveryMethod::where('is_free_delivery','no')->where('status',1)->get();
            Cart::instance('cart')->restore('userID_'.$user_id);

            $cart_data = [];

            $cart_items = Cart::content();
            $cart_loop=0;
            foreach($cart_items as $key => $items)
            {
                $product = Product::find($items->id);
                
                $cart_data[$cart_loop]['uniqueId']      = $items->getUniqueId();
                $cart_data[$cart_loop]['product_id']    = $items->id;
                $cart_data[$cart_loop]['product_name']  = $items->name;
                $cart_data[$cart_loop]['product_code']  = $product->code;
                $cart_data[$cart_loop]['product_sku']   = $items->options['variant_sku'];
                $cart_data[$cart_loop]['product_image'] = $items->options['product_img'];
                $cart_data[$cart_loop]['qty']           = $items->quantity;
                $cart_data[$cart_loop]['variant_id']    = $items->options['variant_id'];

                $category_slug = $product->category->search_engine_name;
                $product_id = base64_encode($product->id);
                $url = url("$category_slug/$product->search_engine_name/$product_id");
                $cart_data[$cart_loop]['link'] = $url;

                $cart_loop++;
            }
            $data['user_id']    = $user_id;
            $data['cart_count'] = Cart::count();
            $data['cart_data']  = $cart_data;
        return response()->json(['success'=> true,'data'=>$data]);
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

    public function RFQLIst()
    {

        $data = array();
        $user_id = Auth::id();
        $all_rfq_data = RFQ::where('customer_id',$user_id)->orderBy('id','desc')->paginate(5);
        
        $rfq_data = array();
        foreach ($all_rfq_data as $key => $rfq) {
            $item_count  = RFQProducts::where('rfq_id',$rfq->id)->count();
            $toatl_qty   = RFQProducts::where('rfq_id',$rfq->id)->sum('quantity');
            $rfq_data[$key]['id'] = $rfq->id;
            $rfq_data[$key]['create_date'] = date('d/m/Y',strtotime($rfq->created_at));
            $rfq_data[$key]['status'] = $rfq->status;
            $rfq_data[$key]['code'] = $rfq->order_no;
            $rfq_data[$key]['item_count'] = $item_count;
            $rfq_data[$key]['toatl_qty'] = $toatl_qty;
            $rfq_data[$key]['sales_rep'] = isset($rfq->salesrep->emp_name)?$rfq->salesrep->emp_name:'';
            $rfq_data[$key]['delivery_method'] = $rfq->deliveryMethod->delivery_method;
            $rfq_data[$key]['status_name'] = OrderStatus::find($rfq->status)->status_name;
            $rfq_data[$key]['show_edit'] =($rfq->status==25)?true:false;


        }

/*        $pagination = array();
        $pagination['firstItem']   = $all_rfq_data->firstItem();
        $pagination['lastItem']    = $all_rfq_data->lastItem();
        $pagination['total']       = $all_rfq_data->total();
        $pagination['currentpage'] = $all_rfq_data->currentpage();
        $pagination['links']       = $all_rfq_data->links();
        $data['pagination']        = $pagination;  */

        $data['rfq_datas'] = $rfq_data;
        return response()->json(['success'=> true,'data'=>$data]);
     
    }

    public function store(Request $request)
    {
        $rules =[
            'delivery_address'    => 'required',
            'billing_address'          => 'required',
            'delevery_method'        => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
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
        $sales_rep_id = User::where('id',$user_id)->value('sales_rep');

        $delivery_charge = DeliveryMethod::find($request->delevery_method);

        $rfq_details=[
            'order_no'            => $rfq_code,
            'status'              => 22,
            'customer_id'         => $user_id,
            'sales_rep_id'        => isset($sales_rep_id)?$sales_rep_id:0,
            'delivery_method_id'  => $request->delevery_method,
            'delivery_charge'     => isset($delivery_charge->amount)?$delivery_charge->amount:0,
            'notes'               => $request->notes,
            'user_id'             => $user_id,
            'delivery_address_id' => $request->delivery_address,
            'billing_address_id'  => $request->billing_address,
            'created_user_type'   => 3,
            'created_at'          => date('Y-m-d H:i:s')
        ];
        $rfq_id = RFQ::insertGetId($rfq_details);

        Cart::instance('cart')->restore('userID_'.$user_id);

        $item_data = [];
        if(isset($request->direct_rfq)){
            $items = Cart::get($request->direct_rfq);
            $item_data[$request->direct_rfq]['uniqueId']      = $items->getUniqueId();
            $item_data[$request->direct_rfq]['product_id']    = $items->id;
            $item_data[$request->direct_rfq]['qty']           = $items->quantity;
            $item_data[$request->direct_rfq]['variant_id']    = $items->options['variant_id'];
        }else{
            $cart_items = Cart::content();
            foreach($cart_items as $key => $items)
            {
                $item_data[$key]['uniqueId']      = $items->getUniqueId();
                $item_data[$key]['product_id']    = $items->id;
                $item_data[$key]['qty']           = $items->quantity;
                $item_data[$key]['variant_id']    = $items->options['variant_id'];
            }
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

        $creater_name=Auth::user()->name;
        $auth_id=Auth::id();
        $rfq_details=RFQ::with('customer','salesrep','statusName')->where('rfq.id',$rfq_id)->first();
        Notification::insert([
            'type'                => 'orders',
            'ref_id'              => $rfq_id,
            'customer_id'         => $auth_id,
            'content'             => $creater_name."'s new RFQ request for ".$rfq_details->order_no,
            'url'                 => url('admin/rfq/'.$rfq_id),
            'created_at'          => date('Y-m-d H:i:s'),
            'created_by'          => $auth_id,
            'created_user_type'   => 3,
        ]);

        $added_rfq_data = RFQ::find($rfq_id);
        $data = $added_rfq_data->order_no;

        return response()->json(['success'=> true,'data'=>$data]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        // $id = base64_decode($id);
        $rfq = $data['rfq'] = RFQ::with('salesrep')->find($id);

        $user = User::find($rfq->customer_id);
        if(isset($rfq->delivery_address_id)&& $rfq->delivery_address_id!=null){
            $del_add_id = $rfq->delivery_address_id;
        }else{
            $del_add_id = $user->address_id;
        }
        $data['cus_email']        = $user->email;
        $data['delivery_address'] = UserAddress::find($del_add_id);

        // $data['admin_address']    = User::where('id',1)->first();

        $data['delivery_method']  = DeliveryMethod::where('is_free_delivery','no')
        ->where('id',$rfq->delivery_method_id)
        ->where('status',1)
        ->value('delivery_method');
        $data['sales_rep']=$rfq->salesrep->emp_name;
        $data['ordered_date']=date('d-m-Y',strtotime($rfq->created_at));
       

        $rfq_products = RFQProducts::with('product','variant','variantvendor')->where('rfq_id',$id)->orderBy('id','desc')->get();
        $rfq_data = $rfq_items = array();
        foreach ($rfq_products as $key => $item) {
            $rfq_items[$key]['product_id'] =  $item->product->id;
            $rfq_items[$key]['product_name'] =  $item->product->name;
            $rfq_items[$key]['variant_sku'] = $item->variantvendor->sku;
            $rfq_items[$key]['quantity'] = $item->quantity;
            $rfq_items[$key]['rfq_price'] = isset($item->rfq_price)?(float)$item->rfq_price:'0.00';
            $rfq_items[$key]['discount_value'] = isset($item->discount_value)?(float)$item->discount_value:'0.00';
            $rfq_items[$key]['discount_type'] = isset($item->discount_type)?(float)$item->discount_type:'0.00';
            $rfq_items[$key]['final_price'] = isset($item->final_price)?(float)$item->final_price:'0.00';
            $rfq_items[$key]['total_price'] = isset($item->total_price)?(float)$item->total_price:'0.00';
            $rfq_items[$key]['sub_total'] = isset($item->sub_total)?(float)$item->sub_total:'0.00';
        }
        $rfq_data['total']       = isset($rfq->total_amount)?(float)$rfq->total_amount:'0.00';
        $rfq_data['discount']    = isset($rfq->order_discount)?(float)$rfq->order_discount:'0.00';
        $rfq_data['tax']         = isset($rfq->order_tax_amount)?(float)$rfq->order_tax_amount:'0.00';
        $rfq_data['grand_total'] = isset($rfq->sgd_total_amount)?(float)$rfq->sgd_total_amount:'0.00';
        $rfq_data['notes']       = isset($rfq->notes)?$rfq->notes:'';
        if(isset($rfq->currency)){
            $rfq_data['currency_code']   = $rfq->currencyCode->currency_code;
            $rfq_data['exchange_amount'] = $rfq->exchange_total_amount;
        }else{
            $rfq_data['currency_code']   = '';
            $rfq_data['exchange_amount'] = '';
        }
        $check_parent = User::where('id',Auth::id())->first();
        $data['check_parent'] = ($check_parent->parent_company==0)?true:false;
        $data['rfq_data']     = $rfq_data;
        $data['rfq_products'] = $rfq_items;
        $data['currency_code'] = $rfq_data['currency_code'];
        $data['data_from']    = '';
        $data['show_edit'] =($rfq->status==25)?true:false;
        if($request->has('child')){
            $data['data_from'] = 'child';
        }
        $data['status_name'] = OrderStatus::find($rfq->status)->status_name;
        //$data['discount_type'] = RFQProducts::where('rfq_id',$id)->groupBy('product_id')->pluck('discount_type','product_id')->toArray();
 // dd($data);
        return response()->json(['success'=> true,'data'=>$data]);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Auth::id();
        $primary_id = Auth::user()->address_id;

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
        $data['delivery_method'] = DeliveryMethod::where('is_free_delivery','no')->where('status',1)->get();
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
            $rfq_items[$key]['final_price'] = isset($item->final_price)?(float)$item->final_price:'0.00';
            $rfq_items[$key]['sub_total'] = isset($item->sub_total)?(float)$item->sub_total:'0.00';
        }

        $rfq_data['total']       = isset($rfq->total_amount)?(float)$rfq->total_amount:'0.00';
        $rfq_data['tax']         = isset($rfq->order_tax_amount)?(float)$rfq->order_tax_amount:'0.00';
        $rfq_data['delivery_charge'] = isset($rfq->delivery_charge)?(float)$rfq->delivery_charge:'0.00';
        $rfq_data['grand_total'] = isset($rfq->sgd_total_amount)?(float)$rfq->sgd_total_amount:'0.00';
        $rfq_data['notes']       = isset($rfq->notes)?$rfq->notes:'';

        $data['rfq_data']     = $rfq_data;
        $data['rfq_products'] = $rfq_items;

        return response()->json(['success'=> true,'data'=>$data]);
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
        $rfq = RFQ::find($id);
        $new_rfq_data = array('delivery_method_id'=>$request->delevery_method,'delivery_address_id'=>$request->delivery_add);
        $old_rfq_data = array('delivery_method_id'=>$rfq->delivery_method_id,'delivery_address_id'=>$rfq->delivery_address_id);
        $rfq_diff = array_diff($new_rfq_data,$old_rfq_data);
        $rfq->total_amount     = $request->total_amount;
        $rfq->sgd_total_amount = $request->grand_total;
        $rfq->save();
        $rfq_products = $request->item;
        $rfq_items = RFQProducts::where('rfq_id',$id)->orderBy('id','desc')->get();
        foreach($rfq_items as $item) {
            $old_item['id'][]  = $item->id;
            $old_item['qty'][] = $item->quantity;
        }

        foreach($rfq_products['id'] as $key => $value) {
            $add = RFQProducts::find($value);
            $add->quantity  = $rfq_products['qty'][$key];
            $add->sub_total = $rfq_products['sub_total'][$key];
            $add->update();
        }
        $rfq_item_diff = array_diff($rfq_products['qty'],$old_item['qty']);

        if((count($rfq_diff)!=0)||(count($rfq_item_diff)!=0)){
            $rfq->status = 22;            
        }
        $rfq->delivery_address_id = $request->delivery_add;
        $rfq->billing_address_id  = $request->billing_add;
        $rfq->delivery_method_id  = $request->delevery_method;
        $rfq->notes               = $request->notes;
        $rfq->save();

        $creater_name=Auth::user()->name;
        $auth_id=Auth::id();
        $rfq_details=RFQ::with('customer','salesrep','statusName')->where('rfq.id',$id)->first();
        Notification::insert([
            'type'                => 'orders',
            'ref_id'              => $id,
            'customer_id'         => $auth_id,
            'content'             => $creater_name."'s RFQ changes and requested for ".$rfq_details->order_no,
            'url'                 => url('admin/rfq/'.$id),
            'created_at'          => date('Y-m-d H:i:s'),
            'created_by'          => $auth_id,
            'created_user_type'   => 3,
        ]);

        return response()->json(['success'=> true,'data'=>[]]);
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
    public function AddAddress(Request $request)
    {
        $rules =[
            'name'  => 'required',
            'mobile'  => 'required',
            'address_line1'  => 'required',
            'country_id'  => 'required',
            'latitude'  => 'required',
            'longitude'  => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }
         $address=[
            'customer_id'       => Auth::id(),
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'address_line1'     => $request->address_line1,
            'address_line2'     => $request->address_line2,
            'country_id'        => $request->country_id,
            'state_id'          => $request->state_id,
            'city_id'           => $request->city_id,
            'post_code'         => $request->post_code,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude
        ];

        $check_order_exists=UserAddress::where('customer_id',Auth::id())->count();
        $address['address_type']=1;
        if ($check_order_exists!=0 && $request->is_default=="yes") {
            UserAddress::where('customer_id',Auth::id())->update(['address_type'=>1]);
            $address['address_type']=0;
        }
        elseif ($check_order_exists==0) {
            $address['address_type']=0;
        }
        $address['created_at']=date('Y-m-d H:i:s');
        $address_id=UserAddress::insertGetId($address);
        $address=UserAddress::find($address_id);
        return response()->json(['success'=> true,'data'=>$address]);
    }
    public function UpdateAddress(Request $request)
    {
        // dd(Auth::id());
        $rules =[
            'address_id'    => 'required',
            'name'          => 'required',
            'mobile'        => 'required',
            'address_line1' => 'required',
            'country_id'    => 'required',
            'latitude'      => 'required',
            'longitude'     => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }

        $address_id=$request->address_id;
         $address=[
            'customer_id'       => Auth::id(),
            'name'              => $request->name,
            'mobile'            => $request->mobile,
            'address_line1'     => $request->address_line1,
            'address_line2'     => $request->address_line2,
            'country_id'        => $request->country_id,
            'state_id'          => $request->state_id,
            'city_id'           => $request->city_id,
            'post_code'         => $request->post_code,
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude
        ];
/*        if($request->is_default=="yes"){
            UserAddress::where('customer_id',Auth::id())->where('address_type',0)->update(['address_type'=>1]);
            $address['address_type']=0;
        }*/
        UserAddress::where('id',$address_id)->update($address);
        $address=UserAddress::find($address_id);
         return response()->json(['success'=> true,'data'=>$address]);
    }
    public function UpdatePrimaryAddress($address_id)
    {
        DB::table('address')->where('customer_id',Auth::id())
        ->where('address_type',0)
        ->update(['address_type'=>1]);

        UserAddress::where('customer_id',Auth::id())->where('id',$address_id)->update(['address_type'=>0]);
        User::where('id',Auth::id())->update(['address_id'=>$address_id]);
        return response()->json(['success'=> true,'data'=>[]]);
    }

    public function AllCountries()
    {
        return Countries::get()->toArray();
    }
    public function StatesBasedCountry($country_id='')
    {
        return State::where('country_id',$country_id)->get()->toArray();
    }
    public function CityBasedState($state_id='')
    {
        return City::where('state_id',$state_id)->get()->toArray();
    }
}
