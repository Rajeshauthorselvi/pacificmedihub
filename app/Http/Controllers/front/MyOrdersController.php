<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\UserAddress;
use App\Models\Countries;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\PaymentHistory;
use App\Models\Currency;
use App\Models\Tax;
use App\Models\PaymentTerm;
use App\Models\CustomerOrderReturn;
use App\Models\CustomerOrderReturnProducts;
use App\Models\Notification;
use Carbon\Carbon;
use App\User;
use Auth;
use Str;
use Session;
use Redirect;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use PDF;

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
        $all_data = Orders::where('customer_id',$user_id)->orderBy('id','desc')->paginate(5);
        
        $order_data = array();
        foreach ($all_data as $key => $item) {
          $item_count  = OrderProducts::where('order_id',$item->id)->count();
          $toatl_qty   = OrderProducts::where('order_id',$item->id)->sum('quantity');
          $order_data[$key]['id'] = $item->id;
          $order_data[$key]['create_date']    = date('d/m/Y',strtotime($item->created_at));
          $order_data[$key]['delivered_at']   = $item->approximate_delivery_date;
          $order_data[$key]['status_id']      = $item->order_status;
          $order_data[$key]['status']         = $item->statusName->status_name;
          $order_data[$key]['color_code']     = $item->statusName->color_codes;
          $order_data[$key]['code']           = $item->order_no;
          $order_data[$key]['item_count']     = $item_count;
          $order_data[$key]['toatl_qty']      = $toatl_qty;
          $order_data[$key]['payment_status'] = $item->payment_status;
          if($item->order_completed_at){
            $from = Carbon::parse($item->order_completed_at)->format('Y-m-d');
            $to   = Carbon::today();
            $diff_in_days = $to->diffInDays($from);
          }else{
            $diff_in_days = 0;
          }
          $order_data[$key]['days_count'] = $diff_in_days;
          $order_data[$key]['order_return'] = CustomerOrderReturn::where('order_id',$item->id)->first();
          $order_data[$key]['total_order_products']=OrderProducts::where('order_id',$item->id)->sum('quantity');
          $order_data[$key]['total_return_quantity']=CustomerOrderReturnProducts::where('order_id',$item->id)
                                                      ->sum('return_quantity');
          $order_data[$key]['total_damage_quantity']=CustomerOrderReturnProducts::where('order_id',$item->id)
                                                      ->sum('damage_quantity');
        }
        $pagination = array();
        $pagination['firstItem']   = $all_data->firstItem();
        $pagination['lastItem']    = $all_data->lastItem();
        $pagination['total']       = $all_data->total();
        $pagination['currentpage'] = $all_data->currentpage();
        $pagination['links']       = $all_data->links();
        $data['pagination']        = $pagination;            

        $data['order_data'] = $order_data;
        // dd($data);
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
      $add_return = new CustomerOrderReturn;
      $add_return->order_id   = $request->order_id;
      $add_return->customer_id = Auth::id();
      $add_return->order_return_status = 22;
      $add_return->notes      = $request->notes;
      $add_return->created_at = date('Y-m-d H:i:s');
      $add_return->save();

      if($add_return){
        $item_details = $request->item;
        foreach($item_details['order_product_id'] as $key => $items) {

          if ($item_details['damaged_qty'][$key] > 0 || $item_details['total_return_qty'][$key] > 0) {
              $add_rtn_prod = new CustomerOrderReturnProducts;
              $add_rtn_prod->customer_order_return_id = $add_return->id;
              $add_rtn_prod->order_id         = $request->order_id;
              $add_rtn_prod->order_product_id = $item_details['order_product_id'][$key];
              $add_rtn_prod->order_product_variant_id = $item_details['order_product_variant_id'][$key];
              $add_rtn_prod->qty_received     = $item_details['recived_qty'][$key];
              $add_rtn_prod->damage_quantity  = $item_details['damaged_qty'][$key];
              $add_rtn_prod->return_quantity  = $item_details['return_qty'][$key];
              $add_rtn_prod->total_return_quantity = $item_details['total_return_qty'][$key];
              $add_rtn_prod->stock_quantity   = $item_details['stock_qty'][$key];
              $add_rtn_prod->timestamps       = false;
              $add_rtn_prod->save();
          }
        }

      }
      $creater_name=Auth::user()->name;
      $auth_id=Auth::id();
      $order_return=Orders::where('id',$request->order_id)->first();
      Notification::insert([
        'type'                => 'orders',
        'ref_id'              => $request->order_id,
        'customer_id'         => $auth_id,
        'content'             => $creater_name."'s Order Return request for ".$order_return->order_no,
        'url'                 => url('admin/stock-in-transit-customer/'.$add_return->id.'/edit'),
        'created_at'          => date('Y-m-d H:i:s'),
        'created_by'          => $auth_id,
        'created_user_type'   => 3,
      ]);
      return redirect()->route('my-orders.index')->with('info', 'return created successfully.!');
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
        $data['admin_address']    = User::where('id',1)->first();
        
        $order_products = OrderProducts::with('product','variant')->where('order_id',$id)->orderBy('id','desc')->get();
        $order_data = $order_items = array();
        foreach ($order_products as $key => $item) {
            $order_items[$key]['product_id'] =  $item->product->id;
            $order_items[$key]['product_variation_id'] =  $item->product_variation_id;
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
            $order_items[$key]['price'] = isset($item->price)?(float)$item->price:'0.00';
            $order_items[$key]['discount_value'] = isset($item->discount_value)?(float)$item->discount_value:'0.00';
            $order_items[$key]['discount_type'] = isset($item->discount_type)?$item->discount_type:'';
            $order_items[$key]['final_price'] = isset($item->final_price)?(float)$item->final_price:'0.00';
            $order_items[$key]['sub_total'] = isset($item->sub_total)?(float)$item->sub_total:'0.00';            
        }

        if($order->order_completed_at){
            $from = Carbon::parse($order->order_completed_at)->format('Y-m-d');
            $to   = Carbon::today();
            $diff_in_days = $to->diffInDays($from);
          }else{
            $diff_in_days = 0;
          }
          $order_data['days_count'] = $diff_in_days;

        $paid_amount = PaymentHistory::where('ref_id',$id)->where('payment_from',2)->sum('amount');
        $order_data['order_return'] = CustomerOrderReturn::where('order_id',$id)->first();
        $order_data['total']       = isset($order->total_amount)?(float)$order->total_amount:'0.00';
        $order_data['tax']         = isset($order->order_tax_amount)?(float)$order->order_tax_amount:'0.00';
        $order_data['grand_total'] = isset($order->exchange_total_amount)?(float)$order->exchange_total_amount:'0.00';
        $order_data['sgd_total'] = isset($order->sgd_total_amount)?(float)$order->sgd_total_amount:'0.00';
        $order_data['paid_amount'] = (float)$paid_amount;
        $order_data['due_amount']  = (float)$order->exchange_total_amount - (float)$paid_amount;
        $order_data['notes']       = isset($order->notes)?$order->notes:'';

        $data['order_data']     = $order_data;
        $data['order_products'] = $order_items;
        $data['currency']       = Currency::where('published',1)->where('id',$order->currency)->value('currency_code');

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

    public function orderReturnView($id)
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
      $data['admin_address']    = User::where('id',1)->first();

      $data['order_return'] = $order_return = CustomerOrderReturn::where('order_id',$id)->first();
      $order_products = OrderProducts::with('product','variant')->where('order_id',$id)->orderBy('id','desc')->get();
      $order_data = $order_items = array();
      foreach ($order_products as $key => $item) {
        $order_items[$key]['id'] =  $item->id;
        $order_items[$key]['product_id'] =  $item->product->id;
        $order_items[$key]['variant_id'] =  $item->variant->id;
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
        $order_items[$key]['qty_received'] = 0;
        $order_items[$key]['damaged_qty'] = 0;
        $order_items[$key]['return_qty']  = 0;
        $order_items[$key]['total_return_qty']  = 0;
        $order_items[$key]['stock_qty']   = 0;
        if(isset($order_return)){
          $return_products = CustomerOrderReturnProducts::where('customer_order_return_id',$order_return->id)->where('order_product_id',$item->product->id)->first();
          $order_items[$key]['qty_received'] = $return_products->qty_received;
          $order_items[$key]['damaged_qty']  = $return_products->damage_quantity;
          $order_items[$key]['return_qty']   = $return_products->return_quantity;
          $order_items[$key]['total_return_qty']   = $return_products->total_return_quantity;
          $order_items[$key]['stock_qty']    = $return_products->stock_quantity;
        }
      }
      $data['order_data']     = $order_data;
      $data['order_products'] = $order_items;
      //dd($data);
      return view('front/customer/orders/order_return',$data);
    }

    public function orderPDF($id)
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }

        $data = array();
        $products = OrderProducts::where('order_id',$id)->groupBy('product_id')->get();
        $product_data = $product_variant = array();
        foreach ($products as $key => $product) {
            $product_name    = Product::where('id',$product->product_id)->value('name');
            $all_variants    = OrderProducts::where('order_id',$id)->where('product_id',$product->product_id)
                                ->pluck('product_variation_id')->toArray();
            $options         = $this->Options($product->product_id);
            $product_variant = $this->Variants($product->product_id,$all_variants);

            $product_data[$product->product_id]=[
              'order_id'        => $id,
              'product_id'      => $product->product_id,
              'product_name'    => $product_name,
              'options'         => $options['options'],
              'option_count'    => $options['option_count'],
              'product_variant' => $product_variant
            ];
        }
        $order = Orders::where('id',$id)->first();

        if ($order->created_user_type==2) {
            $creater_name=Employee::where('id',$order->user_id)->first();
            $creater_name=$creater_name->emp_name;
        }
        else{
            $creater_name=User::where('id',$order->user_id)->first();
            $creater_name=$creater_name->name;
        }
        $data['creater_name']=$creater_name;

        $data['orders']           = $order;
        $data['admin_address']    = User::where('id',1)->first();
        $data['customer_address'] = User::with('address')->where('id',$order->customer_id)->first();
        $data['product_datas']    = $product_data;
        $data['order_id']         = $id;
        $data['taxes']            = Tax::where('published',1)->where('is_deleted',0)->get();
        $data['payment_terms']    = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();  
        $data['currencies']       = Currency::where('is_deleted',0)->where('published',1)->get();

        //return view('front/customer/orders/order_pdf',$data);

        $layout = View::make('front.customer.orders.order_pdf',$data);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadHTML($layout->render());
        return $pdf->download('RFQ-'.$order->order_no.'.pdf');
    }


    public function Variants($product_id,$variation_id=0)
    {
      $variant = ProductVariant::where('product_id',$product_id)->where('disabled',0)->where('is_deleted',0)->first();

      if ($variation_id!=0) {
        $productVariants = ProductVariant::where('product_id',$product_id)->where('disabled',0)
                            ->where('is_deleted',0)
                            ->whereIn('id',$variation_id)->get();
      }
      else{
        $productVariants = ProductVariant::where('product_id',$product_id)->where('disabled',0)->where('is_deleted',0)->get();
      }
      $product_variants = array();
      foreach ($productVariants as $key => $variants) {            
        $variant_details = ProductVariantVendor::where('product_id',$variants->product_id)
                            ->where('product_variant_id',$variants->id)
                            ->where('display_variant',1)
                            ->first();
        $product_variants[$key]['variant_id'] = $variants->id;
        $product_variants[$key]['product_name']=Product::where('id',$variants->product_id)->value('name');
        $product_variants[$key]['product_id']=$product_id;

        if(($variant->option_id!=NULL)&&($variant->option_id2==NULL)&&($variant->option_id3==NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
          $product_variants[$key]['option_id1'] = $variants->option_id;
          $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
          $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3==NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
          $product_variants[$key]['option_id1'] = $variants->option_id;
          $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
          $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
          $product_variants[$key]['option_id2'] = $variants->option_id2;
          $product_variants[$key]['option_value_id2'] = $variants->option_value_id2;
          $product_variants[$key]['option_value2'] = $variants->optionValue2->option_value;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
          $product_variants[$key]['option_id1'] = $variants->option_id;
          $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
          $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
          $product_variants[$key]['option_id2'] = isset($variants->option_id2)?$variants->option_id2:'';
          $product_variants[$key]['option_value_id2']=isset($variants->option_value_id2)?$variants->option_value_id2:'';
          $product_variants[$key]['option_value2'] = isset($variants->optionValue2->option_value)?$variants->optionValue2->option_value:'';
          $product_variants[$key]['option_id3'] = isset($variants->option_id3)?$variants->option_id3:'';
          $product_variants[$key]['option_value_id3']=isset($variants->option_value_id3)?$variants->option_value_id3:'';
          $product_variants[$key]['option_value3'] = isset($variants->optionValue3->option_value)?$variants->optionValue3->option_value:'';
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5==NULL))
        {
          $product_variants[$key]['option_id1'] = $variants->option_id;
          $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
          $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
          $product_variants[$key]['option_id2'] = isset($variants->option_id2)?$variants->option_id2:'';
          $product_variants[$key]['option_value_id2']=isset($variants->option_value_id2)?$variants->option_value_id2:'';
          $product_variants[$key]['option_value2'] = isset($variants->optionValue2->option_value)?$variants->optionValue2->option_value:'';
          $product_variants[$key]['option_id3'] = isset($variants->option_id3)?$variants->option_id3:'';
          $product_variants[$key]['option_value_id3']=isset($variants->option_value_id3)?$variants->option_value_id3:'';
          $product_variants[$key]['option_value3'] = isset($variants->optionValue3->option_value)?$variants->optionValue3->option_value:'';
          $product_variants[$key]['option_id4'] = isset($variants->option_id4)?$variants->option_id4:'';
          $product_variants[$key]['option_value_id4']=isset($variants->option_value_id4)?$variants->option_value_id4:'';
          $product_variants[$key]['option_value4'] = isset($variants->optionValue4->option_value)?$variants->optionValue4->option_value:'';
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5!=NULL))
        {
          $product_variants[$key]['option_id1'] = $variants->option_id;
          $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
          $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
          $product_variants[$key]['option_id2'] = isset($variants->option_id2)?$variants->option_id2:'';
          $product_variants[$key]['option_value_id2']=isset($variants->option_value_id2)?$variants->option_value_id2:'';
          $product_variants[$key]['option_value2'] = isset($variants->optionValue2->option_value)?$variants->optionValue2->option_value:'';
          $product_variants[$key]['option_id3'] = isset($variants->option_id3)?$variants->option_id3:'';
          $product_variants[$key]['option_value_id3']=isset($variants->option_value_id3)?$variants->option_value_id3:'';
          $product_variants[$key]['option_value3'] = isset($variants->optionValue3->option_value)?$variants->optionValue3->option_value:'';
          $product_variants[$key]['option_id4'] = isset($variants->option_id4)?$variants->option_id4:'';
          $product_variants[$key]['option_value_id4']=isset($variants->option_value_id4)?$variants->option_value_id4:'';
          $product_variants[$key]['option_value4'] = isset($variants->optionValue4->option_value)?$variants->optionValue4->option_value:'';
          $product_variants[$key]['option_id5'] = isset($variants->option_id5)?$variants->option_id5:'';
          $product_variants[$key]['option_value_id5']=isset($variants->option_value_id5)?$variants->option_value_id5:'';
          $product_variants[$key]['option_value5'] = isset($variants->optionValue5->option_value)?$variants->optionValue5->option_value:'';
        }

        $product_variants[$key]['base_price'] = $variant_details->base_price;
        $product_variants[$key]['retail_price'] = $variant_details->retail_price;
        $product_variants[$key]['minimum_selling_price'] = $variant_details->minimum_selling_price;
        $product_variants[$key]['display_order'] = $variant_details->display_order;
        $product_variants[$key]['stock_quantity'] = $variant_details->stock_quantity;
        $product_variants[$key]['display_variant'] = $variant_details->display_variant;
        $product_variants[$key]['vendor_id'] = $variant_details->vendor_id;
        $product_variants[$key]['vendor_name'] = $variant_details->vendorName->name;
      }
      return $product_variants;
    }
    public function Options($id)
    {
        $variant = ProductVariant::where('product_id',$id)->where('disabled',0)->where('is_deleted',0)->first();

        $options = array();
        
        if(($variant->option_id!=NULL)&&($variant->option_id2==NULL)&&($variant->option_id3==NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $option_count = 1;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3==NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $option_count = 2;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $option_count = 3;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $options[] = $variant->optionName4->option_name;
            $option_count = 4;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5!=NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $options[] = $variant->optionName4->option_name;
            $option_count = 5;
        }

        return ['options'=>$options,'option_count'=>$option_count];
    }
}
