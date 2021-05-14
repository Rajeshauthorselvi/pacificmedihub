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
