<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use App\Models\RFQ;
use App\Models\RFQProducts;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Vendor;
use App\Models\Employee;
use App\Models\Prefix;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\PaymentHistory;
use App\Models\Currency;
use App\Models\Tax;
use App\Models\PaymentTerm;
use App\Models\UserCompanyDetails;
use App\Models\UserAddress;
use App\Models\OrderHistory;
use App\Models\Purchase;
use App\User;
use App\Models\DeliveryMethod;
use Auth;
use DB;
use Redirect;
use Session;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);
        $data=array();
        $data=$this->RouteLinks();
        $data['edit_permission']=$data['delete_permission']="yes";
        if (!Auth::check() && Auth::guard('employee')->check()) {
          $this->CheckPermission('read',$currenct_route[0]);
          $data['edit_permission']=$this->CheckPermission('update',$currenct_route[0],'yes')['status'];
          $data['delete_permission']=$this->CheckPermission('delete',$currenct_route[0],'yes')['status'];
        }



        $orders=Orders::with('customer','salesrep','statusName','deliveryStatus','address');
        if (!Auth::check() && Auth::guard('employee')->check() && Auth::guard('employee')->user()->emp_department==1) {
            $orders->where('sales_rep_id',Auth::guard('employee')->user()->id);
        }
        
        
        $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)
                                  ->pluck('payment_method','id')
                                  ->toArray();

        $data['type']=$currenct_route[0];

        if (isset($data['view'])) {
          $view=$data['view'];
        }
        else{
          $view='admin.orders.index';
        }

        $data['data_title']="List Orders";
        if ($currenct_route[0]=="new-orders") {
          $orders->whereIn('order_status',[19,20]);
          $data['data_title']='New Orders';
        }
        elseif($currenct_route[0]=="assign-shippment"){
          $orders->whereIn('order_status',[18,15,14]);
          $data['data_title']='Assign for Delivery';
        }
        elseif($currenct_route[0]=="assign-delivery"){
          $orders->where('order_status',15);
          $data['data_title']='Delivery  In Progress';
        }
        elseif($currenct_route[0]=="completed-orders"){
          $orders->where('order_status',13);
          $data['data_title']='Completed Orders';
        }
        elseif($currenct_route[0]=="cancelled-orders"){
          $orders->whereIn('order_status',[21,17,11]);
          $data['data_title']='Cancelled/Missed Orders';

        }

      $orders=$orders->orderBy('orders.id','desc')->get();
      $data['orders']=$orders;


      if($currenct_route[0]=="assign-delivery"){

        $base_location=UserCompanyDetails::where('id',1)->first();
        $base_location = array('lat'=>$base_location->latitude,'lng' => $base_location->longitude);
        $total_orders = array();

        foreach ($orders as $key => $order)
        {
          $a = $base_location['lat'] - $order->address->latitude;
          $b = $base_location['lng'] - $order->address->longitude;
          $distance = sqrt(($a**2) + ($b**2));

          $total_orders[$order->id] = [
              'distance'=>$distance,
              'orders'  => $order
          ];
        }
        asort($total_orders);
        $data['orders']=$total_orders;

      }

        return view($view,$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);

        if (!Auth::check() && Auth::guard('employee')->check()) {
          $this->CheckPermission('create',$currenct_route[0]);
        }

        $rfq_id=$request->rfq_id;
        $data=array();
        $data=$this->RouteLinks();
        $data['rfqs']           = RFQ::with('customer','salesrep','statusName')->where('rfq.id',$rfq_id)->first();     
        $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();

        $customer_details=DB::table('users as u')
        ->select('u.first_name','u.id','ucd.sales_rep')
        ->leftjoin('user_company_details as ucd','ucd.customer_id','u.id')
        ->where('is_deleted',0)->where('status',1)
        ->where('role_id',7)->get()
        ->toArray();
        $data['customers']      = $customer_details;
                                        
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();

        $data['order_status']   = OrderStatus::where('status',1)->whereIn('id',[19,18,20,21])->pluck('status_name','id')
                                        ->toArray();
        $data['payment_method'] = PaymentMethod::where('status',1)->pluck('payment_method','id')->toArray();
        $data['currencies']     = Currency::where('is_deleted',0)->where('published',1)->get();
        $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();
                                        
        $data['delivery_methods'] = DeliveryMethod::all();
        $data['free_delivery'] = DeliveryMethod::where('is_free_delivery','yes')->where('status',1)->value('amount');

        $data['order_code']= '';
        $order_code = Prefix::where('key','prefix')->where('code','order_no')->value('content');
        if (isset($order_code)) {
            $value = unserialize($order_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Orders::count();
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
            $replace_year = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
            $data['order_code']=$replace_number;
        }
        return view('admin.orders.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(request(),[
            'order_status'   => 'required',
            'payment_status' => 'required'
        ]);

        /*Order*/
        if($request->order_status==13){
            $order_completed_at = date('Y-m-d H:i:s');
        }else{
            $order_completed_at = NULL;
        }

       if (!Auth::check() && Auth::guard('employee')->check()) {
          $created_user_type=2;
          $auth_id=Auth::guard('employee')->user()->id;
       }
       else{
          $created_user_type=1;
          $auth_id=Auth::id();
       }
       $customer_address=User::where('id',$request->customer_id)->value('address_id');
        $order_data=[
            'rfq_id'                => $request->rfq_id,
            'sales_rep_id'          => $request->sales_rep_id,
            'customer_id'           => $request->customer_id,
            'order_no'              => $request->order_no,
            'order_status'          => $request->order_status,
            'order_tax'             => $request->order_tax,
            'order_discount'        => $request->order_discount,
            'delivery_method_id'    => $request->delivery_method_id,
            'delivery_charge'       => $request->delivery_charge,
            'currency'              => $request->currency,
            'payment_term'          => $request->payment_term,
            'payment_status'        => $request->payment_status,
            'order_tax_amount'      => $request->order_tax_amount,
            'total_amount'          => $request->total_amount,
            'sgd_total_amount'      => $request->sgd_total_amount,
            'exchange_total_amount' => $request->exchange_rate,
            'user_id'               => $auth_id,
            'created_user_type'     => $created_user_type,
            'notes'                 => $request->note,
            'order_completed_at'    => $order_completed_at,
            'address_id'            => $customer_address,
            'created_at'            => date('Y-m-d H:i:s')
       ];
       
        $order_id = Orders::insertGetId($order_data);
        /*Order*/

        /*Order Products*/
        $quantites             = $request->quantity;
        $variant               = $request->variant;
        $product_ids           = $variant['product_id'];
        $variant_id            = $variant['id'];
        $base_price            = $variant['base_price'];
        $retail_price          = $variant['retail_price'];
        $minimum_selling_price = $variant['minimum_selling_price'];
        $stock_qty             = $variant['stock_qty'];
        $sub_total             = $variant['sub_total'];
        $final_price           = $variant['final_price'];

        foreach ($product_ids as $key => $product_id) {
            if ($stock_qty[$key]!=0) {
                $data=[
                    'order_id'                  => $order_id,
                    'product_id'                => $product_id,
                    'product_variation_id'      => $variant_id[$key],
                    'base_price'                => $base_price[$key],
                    'retail_price'              => $retail_price[$key],
                    'minimum_selling_price'     => $minimum_selling_price[$key],
                    'quantity'                  => $stock_qty[$key],
                    'sub_total'                 => $sub_total[$key],
                    'final_price'               => $final_price[$key]
                ];
                OrderProducts::insert($data);
            }
        }
        /*Order Products*/

        /*Order Payment*/
       if ($request->amount!="" || $request->amount!=0) {
           PaymentHistory::insert([
                'ref_id'  => $order_id,
                'reference_no'  => $request->payment_ref_no,
                'payment_from'  => 2,
                'amount'  => $request->amount,
                'payment_notes' => $request->payment_note,
                'payment_id' => $request->paying_by,
                'created_at' => date('Y-m-d H:i:s')
           ]);

            $total_amount=$request->sgd_total_amount;
            $total_paid=$request->amount;
            $balance_amount=$total_amount-$total_paid;


            if ($balance_amount==0) 
              $payment_status=1;
            else
              $payment_status=2; 


          Orders::where('id',$order_id)->update(['payment_status'=>$payment_status]);
       }
       /*Order Payment*/

       /*OrderHistory*/
         if (!Auth::check() && Auth::guard('employee')->check()) {
            $updated_by=Auth::guard('employee')->user()->id;
            $user_type=2;
         }
         else{
            $updated_by=Auth::id();
            $user_type=1;
         }
         $order_history=[
            'order_id'    => $order_id,
            'order_status_id' => $order_data['order_status'],
            'updated_by'  => $updated_by,
            'user_type'   => $user_type 
         ];
         OrderHistory::insert($order_history);
       /*OrderHistory*/

       $route=$this->RouteLinks();
       return Redirect::route($route['back_route'])->with('success','Order details updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders,$order_id)
    {
        $orders = Orders::with('customer','salesrep','statusName')->where('orders.id',$order_id)->first();
        $data=$this->RouteLinks();
        $data['order']            = $orders;
        $data['currencies']       = Currency::where('is_deleted',0)->where('published',1)->get();
        $data['payment_terms']    = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();
        $data['taxes']            = Tax::where('published',1)->where('is_deleted',0)->get();
        $data['admin_address']    = UserCompanyDetails::where('customer_id',1)->first();
        $data['customer_address'] = User::with('address')->where('id',$orders->customer_id)->first();

      if ($orders->created_user_type==2) {
        $creater_name=Employee::where('id',$orders->user_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$orders->user_id)->first();
        $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
      }
      $data['creater_name']=$creater_name;

        $products = OrderProducts::where('order_id',$order_id)->groupBy('product_id')->get();
        $product_data = $product_variant = array();
        foreach ($products as $key => $product) {
            $product_name    = Product::where('id',$product->product_id)->value('name');
            $options         = $this->Options($product->product_id);
            $all_variants    = OrderProducts::where('order_id',$order_id)->where('product_id',$product->product_id)
                                ->pluck('product_variation_id')->toArray();
            $product_variant = $this->Variants($product->product_id,$all_variants);
            $product_data[$product->product_id] = [
                'order_id'        => $order_id,
                'product_id'      => $product->product_id,
                'product_name'    => $product_name,
                'options'         => $options['options'],
                'option_count'    => $options['option_count'],
                'product_variant' => $product_variant
            ];
        }
        $data['product_datas'] = $product_data;
        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);
        $data['order_status']   = OrderStatus::where('status',1)
                                  ->whereIn('id',[3,13,11])
                                  ->pluck('status_name','id')->toArray();
        $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',7)->pluck('first_name','id')->toArray();
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
        $data['delivery_status']=[''=>'Please Select']+OrderStatus::where('status',1)
                                  ->whereIn('id',[14,15,16,17])
                                  ->pluck('status_name','id')->toArray();
        $data['delivery_persons']=[''=>'Please Select']+Employee::where('emp_department',3)
                                  ->where('is_deleted',0)->where('status',1)
                                  ->pluck('emp_name','id')->toArray();
        if ($currenct_route[0]=="assign-shippment" || $currenct_route[0]=="assign-delivery") {
           return view('admin.orders.assign_shippment_delivery.show',$data);
        }
        return view('admin.orders.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders,$order_id,Request $request)
    {
        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);

        if (!Auth::check() && Auth::guard('employee')->check()) {
          $this->CheckPermission('update',$currenct_route[0]);
        }


        $low_stock=$request->low_stock;
        $data=$this->RouteLinks();
        $data['order'] =$order= Orders::with('customer','salesrep','statusName')->where('orders.id',$order_id)
                                        ->first();
        $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',7)->pluck('first_name','id')->toArray();
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
   
        $data['payment_method'] = PaymentMethod::where('status',1)->pluck('payment_method','id')->toArray();
        $data['currencies']     = Currency::where('is_deleted',0)->where('published',1)->get();
        $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();
        $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();

        $products = OrderProducts::where('order_id',$order_id)->groupBy('product_id')->get();

        $product_data = $product_variant=array();

        foreach ($products as $key => $product) {
            $product_name    = Product::where('id',$product->product_id)->value('name');
            $options         = $this->Options($product->product_id);
            $all_variants    = OrderProducts::where('order_id',$order_id)->where('product_id',$product->product_id)
                                    ->pluck('product_variation_id')
                                    ->toArray();
            $product_variant = $this->Variants($product->product_id,$all_variants);
            $product_data[$product->product_id] = [
                'order_id'        => $order_id,
                'product_id'      => $product->product_id,
                'product_name'    => $product_name,
                'options'         => $options['options'],
                'option_count'    => $options['option_count'],
                'product_variant' => $product_variant
            ];
        }
     
        $data['check_quantity']=Orders::CheckQuantity($order_id);
        $data['product_datas']=$product_data;
        $data['delivery_methods'] = DeliveryMethod::all();
        $data['free_delivery'] = DeliveryMethod::where('is_free_delivery','yes')->where('status',1)->value('amount');

        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);
       
        $data['delivery_persons']=[''=>'Please Select']+Employee::where('emp_department',3)
                                  ->where('is_deleted',0)->where('status',1)
                                  ->pluck('emp_name','id')->toArray();

         $order_status=OrderStatus::where('status',1);  
                                 
        if ($currenct_route[0]=="assign-delivery") {
           $order_status->whereIn('id',[16,17]);
        }                     
        elseif ($currenct_route[0]=="assign-shippment") {
            if($order->order_status==18){
              $order_status->whereIn('id',[14,18]);   
            }
            else{
               $order_status->whereIn('id',[14,15,18,16,17]);
            }
        }
        elseif($currenct_route[0]=="new-orders"){
          if ($low_stock=="yes") {
             $order_status->whereIn('id',[19,20,21]);
          }
          else{
            $order_status->whereIn('id',[19,20,21,18]);
          }
        }
        elseif ($currenct_route[0]=="cancelled-orders") {
          $order_status->whereIn('id',[17,18,19,20]);
        }

        $order_status=$order_status->pluck('status_name','id')->toArray();

        $data['order_status']=[''=>'Please Select']+$order_status;

        if ($currenct_route[0]=="assign-shippment" || $currenct_route[0]=="assign-delivery") {
           return view('admin.orders.assign_shippment_delivery.edit',$data);
        }
        return view('admin.orders.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      
      $variant=$request->variant;
      $new_variant=$request->new_variant;


        $currenct_route=Route::currentRouteName();
        $route=explode('.',$currenct_route);
        $data['type']=$currenct_route[0];

        $this->validate(request(),[
            'order_status'   => 'required',
        ]);
            
        if($request->order_status==13){
            $order_completed_at = date('Y-m-d H:i:s');
        }else{
            $order_completed_at = NULL;
        }

        if ($route[0]=="assign-shippment" || $route[0]=="assign-delivery") {

          $order_data['delivery_person_id']=$request->delivery_person_id;
          if ($request->order_status==15) {
            $order_data['approximate_delivery_date']=date('Y-m-d');
          }
          $order_data['logistic_instruction']=$request->notes;
          // $order_data['delivery_status']=$request->delivery_status;
          $order_data['order_status']=$request->order_status;

          if ($request->order_status==16) {
              $order_data['delivered_at']=date('Y-m-d H:i:s');
              $order_data['order_status']=13;
          }
        }
        else{
          $order_data=[
              'sales_rep_id'          => $request->sales_rep_id,
              'customer_id'           => $request->customer_id,
              'order_status'          => $request->order_status,
              'order_tax'             => $request->order_tax,
              'order_discount'        => $request->order_discount,
              'delivery_method_id'    => $request->delivery_method_id,
              'delivery_charge'       => $request->delivery_charge,
              'payment_term'          => $request->payment_term,
              'payment_status'        => $request->payment_status,
              'payment_ref_no'        => $request->payment_ref_no,
              'paid_amount'           => $request->paid_amount,
              'paying_by'             => $request->paying_by,
              'payment_note'          => $request->payment_note,
              'notes'                 => $request->note,
              'currency'              => $request->currency,
              'order_tax_amount'      => $request->order_tax_amount,
              'total_amount'          => $request->total_amount,
              'sgd_total_amount'      => $request->sgd_total_amount,
              'exchange_total_amount' => $request->exchange_rate,
              'order_completed_at'    => $order_completed_at,
              'updated_at'            => date('Y-m-d H:i:s')
          ];
        }

      Orders::where('id',$id)->update($order_data);

      if ($request->order_status==19) {
        $existing_product_id=$new_product_variant=array();
        if (isset($variant['product_id'])) {
          $existing_product_id=array_unique($variant['product_id']);
        }
        if (isset($new_variant['product_id'])) {
          $new_product_variant=array_unique($new_variant['product_id']);
        }

        $active_product_ids=array_merge($existing_product_id,$new_product_variant);
        $deleted_products=OrderProducts::where('order_id',$id)->whereNotIn('product_id',$active_product_ids)
                          ->pluck('product_id')->toArray();

        if(isset($deleted_products)) {
          OrderProducts::where('order_id',$id)->whereIn('product_id',array_unique($deleted_products))->delete();
        }


        $variant               = $request->variant;
        $row_ids               = $variant['row_id'];
        $stock_qty             = $variant['stock_qty'];
        $final_price             = $variant['final_price'];
        $sub_total             = $variant['sub_total'];
        foreach ($row_ids as $key => $row_id) {
          $data=[
            'quantity'   => $stock_qty[$key],
            'final_price'  => $final_price[$key],
            'sub_total'  => $sub_total[$key],
          ];
          OrderProducts::where('id',$row_id)->update($data);
        }
        if ($request->has('new_variant')) {

            $variant               = $request->new_variant;
            $product_ids           = $variant['product_id'];
            $variant_id            = $variant['id'];
            $base_price            = $variant['base_price'];
            $retail_price          = $variant['retail_price'];
            $minimum_selling_price = $variant['minimum_selling_price'];
            $stock_qty             = $variant['stock_qty'];
            $final_price             = $variant['final_price'];
            $sub_total             = $variant['sub_total'];

          foreach ($product_ids as $key => $product_id) {
            if ($stock_qty[$key]!=0 && $stock_qty[$key]!="") {
              $data=[
                      'order_id'                  => $id,
                      'product_id'                => $product_id,
                      'product_variation_id'      => $variant_id[$key],
                      'base_price'                => $base_price[$key],
                      'retail_price'              => $retail_price[$key],
                      'minimum_selling_price'     => $minimum_selling_price[$key],
                      'quantity'                  => $stock_qty[$key],
                      'sub_total'                 => $sub_total[$key],
                      'final_price'               => $final_price[$key]
                  ];
              OrderProducts::insert($data);
            }
          }
        }
      }

       if (!Auth::check() && Auth::guard('employee')->check()) {
          $updated_by=Auth::guard('employee')->user()->id;
          $user_type=2;
       }
       else{
          $updated_by=Auth::id();
          $user_type=1;
       }


       $order_history=[
          'order_id'    => $id,
          'order_status_id' => $order_data['order_status'],
          'updated_by'  => $updated_by,
          'user_type'   => $user_type 
       ];
       OrderHistory::insert($order_history);

       if ($request->order_status==14) {
         $this->ReduceQuantityToVendor($id);
       }
       elseif ($request->order_status==17) {
          $this->UpdateQuantityToStock($id);
       }

       $route=$this->RouteLinks();
       return Redirect::route($route['back_route'])->with('success','Order details updated successfully');

    }
    public function UpdateQuantityToStock($order_id)
    {
        $stock_quantity=Orders::where('id',$order_id)->value('quantity_deducted');
        $stock_quantitys=unserialize($stock_quantity);
        foreach ($stock_quantitys as $key => $values) {
            foreach ($values as $vendor_id => $products) {
                foreach ($products as $product_id => $product) {
                      foreach ($product as $variant_id => $quantity) {
                        $existing_quantity=ProductVariantVendor::where('product_id',$product_id)
                        ->where('product_variant_id',$variant_id)
                        ->where('vendor_id',$vendor_id)
                        ->value('stock_quantity');

                        ProductVariantVendor::where('product_id',$product_id)
                        ->where('product_variant_id',$variant_id)
                        ->where('vendor_id',$vendor_id)
                        ->update(['stock_quantity'=>($existing_quantity+$quantity)]);
                      }
                }
            }
        }
    }
    public function ReduceQuantityToVendor($order_id='')
    {
        $order_products=OrderProducts::where('order_id',$order_id)
                        ->groupBy('product_id','product_variation_id')
                        ->get();

        foreach ($order_products as $key => $products) {
          $variant_vendor_details=ProductVariantVendor::where('product_id',$products->product_id)
                                  ->where('product_variant_id',$products->product_variation_id)
                                  // ->orderBy('minimum_selling_price','DESC')
                                  ->orderBy('stock_quantity','DESC')
                                  ->pluck('stock_quantity','vendor_id')->toArray();
          $total_quantity_detucted=array();
          foreach ($variant_vendor_details as $vendor_id => $stock_quantity) {
            if($products->quantity >= $stock_quantity){
                $products->quantity -=  $stock_quantity; 
                $stock_quantity=0;

                ProductVariantVendor::where('vendor_id',$vendor_id)
                ->where('product_id',$products->product_id)
                ->where('product_variant_id',$products->product_variation_id)
                ->where('vendor_id',$vendor_id)
                ->update(['stock_quantity'=>$stock_quantity]);

                $this->CheckQuantityAndUpdate($order_id,$vendor_id,$products->product_id,$products->product_variation_id,$products->quantity);
            }
            elseif ($products->quantity <= $stock_quantity) {
             
                $balance_quantity=($stock_quantity-$products->quantity);
              
                ProductVariantVendor::where('vendor_id',$vendor_id)
                ->where('product_id',$products->product_id)
                ->where('product_variant_id',$products->product_variation_id)
                ->where('vendor_id',$vendor_id)
                ->update(['stock_quantity'=>$balance_quantity]);

              $this->CheckQuantityAndUpdate($order_id,$vendor_id,$products->product_id,$products->product_variation_id,$products->quantity);

             break;
            }
            else
            {
               $stock_quantity -=$products->quantity;
                ProductVariantVendor::where('vendor_id',$vendor_id)
                ->where('product_id',$products->product_id)
                ->where('product_variant_id',$products->product_variation_id)
                ->where('vendor_id',$vendor_id)
                ->update(['stock_quantity'=>$stock_quantity]);

                $this->CheckQuantityAndUpdate($order_id,$vendor_id,$products->product_id,$products->product_variation_id,$stock_quantity);
            }


            if ($products->quantity==0)  break;
          }

        }
      
    }
    public function CheckQuantityAndUpdate($order_id,$vendor_id,$product_id,$variant_id,$stock_quantity)
    {
      $order_quantity=Orders::where('id',$order_id)->value('quantity_deducted');
      if (isset($order_quantity)) {
          $unse_quantity=unserialize($order_quantity);
          $data[$vendor_id]=[
            $vendor_id=>[
                $product_id=>[
                  $variant_id=>$stock_quantity
                ]
            ],
          ];
          $unse_quantit=array_merge($unse_quantity,$data);
          Orders::where('id',$order_id)->update(['quantity_deducted'=>serialize($unse_quantit)]);
      }
      else{

          $data[$vendor_id]=[
            $vendor_id=>[
                $product_id=>[
                  $variant_id=>$stock_quantity
                ]
            ],
          ];
          Orders::where('id',$order_id)->update(['quantity_deducted'=>serialize($data)]);
      }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);

        if (!Auth::check() && Auth::guard('employee')->check()) {
          $this->CheckPermission('delete',$currenct_route[0]);
        }

        OrderHistory::where('order_id',$id)->delete();
        $delete_order = Orders::where('id',$id)->delete();
        if($delete_order){
          OrderProducts::where('order_id',$id)->delete();
        }
        $route=$this->RouteLinks();
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route($route['back_route'])->with('error','Order deleted successfully.!');
    }


    public function rfqToOrder($id)
    {
        $order_code = Prefix::where('key','prefix')->where('code','order_no')->value('content');
        if (isset($order_code)) {
            $value = unserialize($order_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Orders::count();
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
            $replace_year = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
            $order_no=$replace_number;
        }

        $rfq = RFQ::find($id);
        $order_data=[
            'rfq_id'                => $rfq->id,
            'sales_rep_id'          => $rfq->sales_rep_id,
            'customer_id'           => $rfq->customer_id,
            'order_no'              => $order_no,
            'order_status'          => 19,
            'order_tax'             => $rfq->order_tax,
            'order_discount'        => $rfq->order_discount,
            'currency'              => $rfq->currency,
            'payment_term'          => $rfq->payment_term,
            'payment_status'        => 3,
            'order_tax_amount'      => $rfq->order_tax_amount,
            'total_amount'          => $rfq->total_amount,
            'sgd_total_amount'      => $rfq->sgd_total_amount,
            'exchange_total_amount' => $rfq->exchange_total_amount,
            'user_id'               => $rfq->user_id,
            'notes'                 => $rfq->notes,
            'created_at'            => date('Y-m-d H:i:s')
        ];
       
        $order_id = Orders::insertGetId($order_data);
       
        $rfq_products = RFQProducts::where('rfq_id',$rfq->id)->get();

        foreach ($rfq_products as $key => $products) {
            OrderProducts::insert([
                'order_id'              => $order_id,
                'product_id'            => $products->product_id,
                'product_variation_id'  => $products->product_variant_id,
                'base_price'            => $products->base_price,
                'retail_price'          => $products->retail_price,
                'minimum_selling_price' => $products->minimum_selling_price,
                'quantity'              => $products->quantity,
                'sub_total'             => $products->sub_total,
                'final_price'           => $products->rfq_price
            ]);
       }

       RFQ::where('id',$rfq->id)->update(['status'=>10]);
       return Redirect::route('new-orders.index')->with('success','Order created successfully...!');
    }

    public function CreatePurchasePayment(Request $request)
    {
        $order_paid_total=PaymentHistory::where('ref_id',$request->id)->where('payment_from',2)->sum('amount');
        $data=[
          'ref_id'          => $request->id,
          'reference_no'    => $request->reference_no,
          'payment_from'    => $request->payment_from,
          'amount'          => $request->amount,
          'payment_notes'   => $request->payment_notes,
          'created_at'      => date('Y-m-d H:i:s'),
          'payment_id'      => $request->payment_id,
        ];
        PaymentHistory::insert($data);
        $total_amount=$request->total_payment;
        $total_paid=$request->amount;
        $balance_amount=$order_paid_total+$total_paid;
        $balance_amount=$balance_amount-$total_amount;
        
        if ($balance_amount==0) 
          $payment_status=1;
        else
          $payment_status=2; 

        Orders::where('id',$request->id)->update(['payment_status'=>$payment_status]);
        return Redirect::back()->with('success','Payment added successfully...!');
    }


    public function ViewPurchasePayment($order_id)
    {
        $all_payment_history = PaymentHistory::with('PaymentMethod')->where('ref_id',$order_id)
                                    ->where('payment_from',2)->get()->toArray();
        return $all_payment_history;
    }

    public function ProductSearch(Request $request)
    {

        $search_type=$request->product_search_type;
        $from=$request->from;
        if ($search_type=="product") {

            $product_names=Product::where("name","LIKE","%".$request->input('name')."%")
                            ->where('is_deleted',0)
                            ->where('published',1)
                            ->pluck('name','id')
                            ->toArray();
            $names=array();
            if (count($product_names)>0) {
                foreach ($product_names as $key => $name) {
                    $names[]=[
                        'value'=>$key,
                        'label'  => $name
                    ];
                }  
            }
            else{
                    $names=[
                        'value'=>'',
                        'label'  => 'No records found'
                    ]; 
            }
            return response()->json($names);
        }
        elseif ($search_type=='product_options') {
            $product_id=$request->product_id;

            if ($product_id=="No records found") {
                return ['status'=>false,'response'=>'No data found'];
            }

            $options=$this->Options($product_id);
            $data['options'] = $options['options'];
            $data['option_count'] = $options['option_count'];
            $data['product_id'] = $product_id;
            $data['product_name']=Product::where('id',$product_id)->value('name');
            $data['product_variant']=$this->Variants($product_id);

            if ($from=="edit") {
              $view=view('admin.orders.edit_variants',$data)->render();
            }
            else{
              $view=view('admin.orders.variants',$data)->render();
            }

            return $view;
        }

    }

    public function Variants($product_id,$variation_id=0)
    {

        $variant = ProductVariant::where('product_id',$product_id)->where('disabled',0)->where('is_deleted',0)->first();
/* $productVariants=ProductVariant::select('product_variants.*')
                               ->select('product_variants.*')
                               ->leftjoin('product_variant_vendors as pvv','pvv.product_variant_id','product_variants.id')
                               ->where('pv.product_id',$product_id)
                               ->whereIn('pv.id',$variation_id)
                               ->where('disabled',0)
                               ->where('is_deleted',0)
                               ->orderBy('minimum_selling_price','DESC')
                               ->groupBy('pvv.product_variant_id')
                               ->get();
$productVariants=ProductVariant::select('product_variants.*')
                               ->leftjoin('product_variant_vendors as pvv','pvv.product_variant_id','product_variants.id')
                               ->where('product_variants.product_id',$product_id)
                               ->where('disabled',0)
                               ->where('is_deleted',0)
                               ->orderBy('minimum_selling_price','DESC')
                               ->groupBy('pvv.product_variant_id')
                               ->get();*/
                               
        if ($variation_id!=0) {
             $productVariants = ProductVariant::where('product_id',$product_id)
                            ->where('disabled',0)->where('is_deleted',0)
                            ->whereIn('id',$variation_id)
                            ->get();
        }
        else{
            $productVariants = ProductVariant::where('product_id',$product_id)
                               ->where('disabled',0)->where('is_deleted',0)
                               ->get();
        }


        $product_variants = array();
        foreach ($productVariants as $key => $variants) {
            
            $variant_details = ProductVariantVendor::where('product_id',$variants->product_id)->where('product_variant_id',$variants->id)->where('display_variant',1)->first();

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
                $product_variants[$key]['option_value_id2'] = isset($variants->option_value_id2)?$variants->option_value_id2:'';
                $product_variants[$key]['option_value2'] = isset($variants->optionValue2->option_value)?$variants->optionValue2->option_value:'';
                $product_variants[$key]['option_id3'] = isset($variants->option_id3)?$variants->option_id3:'';
                $product_variants[$key]['option_value_id3'] = isset($variants->option_value_id3)?$variants->option_value_id3:'';
                $product_variants[$key]['option_value3'] = isset($variants->optionValue3->option_value)?$variants->optionValue3->option_value:'';
            }
            elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5==NULL))
            {
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
                $product_variants[$key]['option_id2'] = isset($variants->option_id2)?$variants->option_id2:'';
                $product_variants[$key]['option_value_id2'] = isset($variants->option_value_id2)?$variants->option_value_id2:'';
                $product_variants[$key]['option_value2'] = isset($variants->optionValue2->option_value)?$variants->optionValue2->option_value:'';
                $product_variants[$key]['option_id3'] = isset($variants->option_id3)?$variants->option_id3:'';
                $product_variants[$key]['option_value_id3'] = isset($variants->option_value_id3)?$variants->option_value_id3:'';
                $product_variants[$key]['option_value3'] = isset($variants->optionValue3->option_value)?$variants->optionValue3->option_value:'';
                $product_variants[$key]['option_id4'] = isset($variants->option_id4)?$variants->option_id4:'';
                $product_variants[$key]['option_value_id4'] = isset($variants->option_value_id4)?$variants->option_value_id4:'';
                $product_variants[$key]['option_value4'] = isset($variants->optionValue4->option_value)?$variants->optionValue4->option_value:'';
            }
            elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5!=NULL))
            {
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
                $product_variants[$key]['option_id2'] = isset($variants->option_id2)?$variants->option_id2:'';
                $product_variants[$key]['option_value_id2'] = isset($variants->option_value_id2)?$variants->option_value_id2:'';
                $product_variants[$key]['option_value2'] = isset($variants->optionValue2->option_value)?$variants->optionValue2->option_value:'';
                $product_variants[$key]['option_id3'] = isset($variants->option_id3)?$variants->option_id3:'';
                $product_variants[$key]['option_value_id3'] = isset($variants->option_value_id3)?$variants->option_value_id3:'';
                $product_variants[$key]['option_value3'] = isset($variants->optionValue3->option_value)?$variants->optionValue3->option_value:'';
                $product_variants[$key]['option_id4'] = isset($variants->option_id4)?$variants->option_id4:'';
                $product_variants[$key]['option_value_id4'] = isset($variants->option_value_id4)?$variants->option_value_id4:'';
                $product_variants[$key]['option_value4'] = isset($variants->optionValue4->option_value)?$variants->optionValue4->option_value:'';
                $product_variants[$key]['option_id5'] = isset($variants->option_id5)?$variants->option_id5:'';
                $product_variants[$key]['option_value_id5'] = isset($variants->option_value_id5)?$variants->option_value_id5:'';
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


    public function RouteLinks()
    {
        $currenct_route=Route::currentRouteName();
        $currenct_route=explode('.',$currenct_route);
        $data['type']=$currenct_route[0];
        if ($currenct_route[0]=="new-orders") {
          $data['back_route']='new-orders.index';
          $data['show_route']='new-orders.show';
          $data['create_route']='new-orders.create';
          $data['edit_route']='new-orders.edit';
          $data['delete_route'] = 'new-orders.destroy';
          $data['store_route']='new-orders.store';
          $data['update_route']='new-orders.update';
          
        }
        elseif($currenct_route[0]=="assign-shippment"){
          $data['back_route']='assign-shippment.index';
          $data['show_route']='assign-shippment.show';
          $data['create_route']='assign-shippment.create';
          $data['edit_route']='assign-shippment.edit';
          $data['store_route']='assign-shippment.store';
          $data['update_route']='assign-shippment.update';
          $data['delete_route'] = 'assign-shippment.destroy';
          $data['type']="new";
          
          $data['view']='admin.orders.assign_shippment_delivery.index';
          // $data['view']='admin.orders.assign_shippment_delivery.delivery_index';
        }
        elseif($currenct_route[0]=="assign-delivery"){
          $data['back_route']='assign-delivery.index';
          $data['show_route']='assign-delivery.show';
          $data['create_route']='assign-delivery.create';
          $data['edit_route']='assign-delivery.edit';
          $data['delete_route'] = 'assign-delivery.destroy';
          $data['store_route']='assign-delivery.store';
          $data['update_route']='assign-delivery.update';
          
          $data['view']='admin.orders.assign_shippment_delivery.delivery_index';
        }
        elseif($currenct_route[0]=="completed-orders"){
          $data['back_route']='completed-orders.index';
          $data['show_route']='completed-orders.show';
          $data['create_route']='completed-orders.create';
          $data['edit_route']='completed-orders.edit';
          $data['delete_route'] = 'completed-orders.destroy';
          $data['store_route']='completed-orders.store';
          $data['update_route']='completed-orders.update';
          
        }
        elseif($currenct_route[0]=="cancelled-orders"){
          $data['back_route']='cancelled-orders.index';
          $data['show_route']='cancelled-orders.show';
          $data['create_route']='cancelled-orders.create';
          $data['edit_route']='cancelled-orders.edit';
          $data['delete_route'] = 'cancelled-orders.destroy';
          $data['store_route']='cancelled-orders.store';
          $data['update_route']='cancelled-orders.update';
          $data['view']='admin.orders.assign_shippment_delivery.index';
          
        }
        else{
          $data['back_route']='new-orders.index';
          $data['show_route']='new-orders.show';
          $data['create_route']='new-orders.create';
          $data['edit_route']='new-orders.edit';
          $data['delete_route'] = 'new-orders.destroy';
          $data['store_route']='new-orders.store';
          $data['update_route']='new-orders.update';
        }

        return $data;
    }
    public function CheckPermission($action='',$page,$check_permission='no')
    {
        if ($page=="new-orders") {
          $route="new_order";
        }
        elseif($page=="assign-shippment"){
          $route="assign_order";
        }
        elseif($page=="assign-delivery"){
          $route="delivery_order";
        }
        elseif($page=="completed-orders"){
          $route="completed_orders";
        }
        elseif($page=="cancelled-orders"){
          $route="cancelled_order";
        }
        else{
          $route="order";
        }
        if ($check_permission=="yes") {
          $status=Auth::guard('employee')->user()->isAuthorized($route,$action);
            return ['status'=>$status];
        }
        else{
          if (!Auth::guard('employee')->user()->isAuthorized($route,$action)) {
             abort(404);
          }
        }
    }
    public function COEmployeePrint($order_id)
    {

    $print_data=$this->PdfAndPrint($order_id);
    
    return view('admin.orders.assign_shippment_delivery.print',$print_data['data']);

    }
    public function COAdminPrint($order_id){
        $print_data=$this->PdfAndPrint($order_id);

      return view('admin.orders.print',$print_data['data']);        
    }

    public function COAdminPdf($order_id){
      $print_data=$this->PdfAndPrint($order_id);
      $data=$print_data['data'];
      $order_details=$data['order'];


      // return view('admin.orders.pdf',$print_data['data']);
      $layout = View::make('admin.orders.pdf',$print_data['data']);
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML($layout->render());
      return $pdf->download('COP-'.$order_details->order_no.'.pdf');
    }

    public function COPEmployeePdf($order_id)
    {
      $print_data=$this->PdfAndPrint($order_id);

      $data=$print_data['data'];
      $order_details=$data['order'];

      $layout = View::make('admin.orders.assign_shippment_delivery.pdf',$print_data['data']);
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML($layout->render());
      return $pdf->download('COP-'.$order_details->order_no.'.pdf');
    }

    public function PdfAndPrint($order_id)
    {
        $data=array();
        $data['order']=$order = Orders::find($order_id);
        $data['admin_address']  = UserCompanyDetails::where('customer_id',1)->first();
        $data['customer_address']  = UserAddress::where('id',$order->address_id)->first();
        $data['customer_gst_number']=UserCompanyDetails::where('customer_id',$order->customer_id)
                                     ->value('company_gst');

          if ($order->created_user_type==2) {
            $creater_name=Employee::where('id',$order->user_id)->first();
            $creater_name=$creater_name->emp_name;
          }
          else{
            $creater_name=User::where('id',$order->user_id)->first();
            $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
          }
        $data['creater_name']=$creater_name;
        $products = OrderProducts::where('order_id',$order_id)->groupBy('product_id')->get();

      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = OrderProducts::where('order_id',$order_id)
                            ->where('product_id',$product->product_id)
                            ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);
        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'order_id'     => $order_id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
    $data['product_data']=$product_data;

         $data['order_code']= '';
        $order_code = Prefix::where('key','prefix')->where('code','invoice')->value('content');
        if (isset($order_code)) {
            $value = unserialize($order_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Orders::count();
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
            $replace_year = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
            $data['order_code']=$replace_number;
        }


    return ['data'=>$data];
    }

    public function OrderPurchase(Request $request,$order_id)
    {

      $data=array();

          $product_id=$request->product_id;
          $variant_id=$request->product_variant_id;
          $vendor_id=$request->vendor_id;

          $data['order_status']   = [''=>'Please Select']+OrderStatus::where('status',1)->whereIn('id',[1,2,8])
                                        ->pluck('status_name','id')->toArray();
          $data['payment_method'] = PaymentMethod::where('status',1)->pluck('payment_method','id')->toArray();
          $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();
          $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();
          $data['purchase_code']='';
          $purchase_code = Prefix::where('key','prefix')->where('code','purchase_no')->value('content');
          if (isset($purchase_code)) {
            $value = unserialize($purchase_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Purchase::count();
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
            $replace_year = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
            $data['purchase_code']=$replace_number;
          }
          $variant_details=$this->VariantIds($order_id);

          $product_ids=$variant_details['product_ids'];

            $order_details=Orders::where('id',$order_id)->first();
            $order_products=OrderProducts::where('order_id',$order_id)
            ->whereIn('product_id',$product_ids)
            // ->groupBy('product_id')
            ->get();
            $product_data=$all_product_ids=array();
            foreach ($order_products as $key => $product) {

            $check_product_quantity=ProductVariantVendor::where('product_id',$product->product_id)->where('product_variant_id',$product->product_variation_id)->sum('stock_quantity');
            if ($check_product_quantity < $product->quantity) {


                $all_variants=$variant_details['variants'];
                $product_name    = Product::where('id',$product->product_id)->value('name');
                $product_variant = $this->Variants($product->product_id,$all_variants);
                $options         = $this->Options($product->product_id);
                $product_data[$product->product_id] = [
                    'order_id'        => $order_id,
                    'product_id'      => $product->product_id,
                    'product_name'    => $product_name,
                    'order_quantity'        => $product->quantity,
                    'total_avail_quantity'  => $check_product_quantity,
                    'options'               => $options['options'],
                    'option_count'          => $options['option_count'],
                    'product_variant'       => $product_variant
                ];
                $all_product_ids[]=$product->product_id;
            }
        }
        $data['purchase_products'] = $product_data;
        $all_vendors=DB::table('product_variant_vendors as pvv')
                   ->leftjoin('vendors as v','v.id','pvv.vendor_id')
                   ->where('status',1)
                   ->whereIn('product_id',$all_product_ids)
                   ->pluck('name','v.id')->toArray();
        $data['vendors']        = [''=>'Please Select']+$all_vendors;
        $data['order_id']=$order_id;
        $data['balance_quantities']=$variant_details['remaining_quantity'];
        return view('admin.orders.create_purchase',$data);
        
    }
    public function VariantIds($order_id='')
    {
        $products=Orders::with('orderProducts')
                    ->where('orders.id',$order_id)
                    ->where('delivery_person_id',0)->first();

        $all_variant_ids=array();
        $all_product_ids=array();
        $remaining_quantity=array();
        if (isset($products->orderProducts)) {
            foreach ($products->orderProducts as $key => $product) {
              // $all_variants=Orders::AllProductVariantIds($product->product_id,$product->product_variation_id);
                $check_product_quantity=ProductVariantVendor::where('product_variant_id',$product->product_variation_id)->sum('stock_quantity');

                if ($check_product_quantity < $product->quantity) {
                    array_push($all_variant_ids, array($product->product_variation_id));
                    array_push($all_product_ids, $product->product_id);

                    $remaining_quantity[$product->product_id][$product->product_variation_id]=($product->quantity-$check_product_quantity);
                }
            }
        }

return ['product_ids'=>$all_product_ids,'variants'=>$all_variant_ids,'remaining_quantity'=>$remaining_quantity];

    }

    public function SummaryReport(Request $request)
    {

        if ($request->ajax()) {
          $order_ids=$request->order_ids;
        }
        else{
          $order_ids=$request->order_ids;
          $order_ids=explode(',', $order_ids);
        }
      $product_datas=array();
      $products = OrderProducts::whereIn('order_id',$order_ids)
      ->groupBy('product_id','product_variation_id')->get();

      foreach ($order_ids as $key => $order_id) {
  $order=Orders::where('orders.id',$order_id)->first(); 
        $product_data = $product_variant = array();
        foreach ($products as $key => $product) {

          $option_values=$this->TotalQuantity($order_ids,$product->product_id,$product->product_variation_id);

          $product_name    = Product::where('id',$product->product_id)->value('name');
          $variants=$this->Variants($product->product_id,[$option_values->product_variation_id]);
          $variant_name="";
          if (isset($variants[0]['option_value1'])) {
              $variant_name=$variants[0]['option_value1'];
          }
          if (isset($variants[0]['option_value2'])) {
             $variant_name=$variants[0]['option_value1'].'-'.$variants[0]['option_value2'];
          }
          if (isset($variants[0]['option_value3'])) {
             $variant_name=$variants[0]['option_value1'].'-'.$variants[0]['option_value2'].'-'.$variants[0]['option_value3'];
          }
          if (isset($variants[0]['option_value4'])) {
             $variant_name=$variants[0]['option_value1'].'-'.$variants[0]['option_value2'].'-'.$variants[0]['option_value3'].'-'.$variants[0]['option_value4'];
          }
          if (isset($variants[0]['option_value5'])) {
             $variant_name=$variants[0]['option_value1'].'-'.$variants[0]['option_value2'].'-'.$variants[0]['option_value3'].'-'.$variants[0]['option_value4'].'-'.$variants[0]['option_value5'];
          }
          $product_data[]=[
            'total_quantity'=>$option_values->total_quantity,
            'product_name'=>$product_name,
            'product_variant' => $variant_name
          ];

        }
        $product_datas[$order->order_no]=$product_data;
        $order_nos[]=$order->order_no;
      }
      asort($order_nos);
      $data['order_nos']=$order_nos;
      $data['product_datas']=$product_data;
    if ($request->ajax()) {
        return ['url'=>url('admin/order-summary?order_ids='.implode(',',$order_ids))];
    }
    else{
      $layout = View::make('admin.orders.assign_shippment_delivery.download_summary',$data);
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML($layout->render());
      return $pdf->download('Order-Summary.pdf');
    }
        
    }


    public function TotalQuantity($order_ids,$product_id,$variant_id)
    {
        $total_quantity=DB::table('order_products as op')
        ->select(DB::raw('sum(quantity) as total_quantity'),'op.product_variation_id')
        ->whereIn('order_id',$order_ids)
        ->where('product_id',$product_id)
        ->where('product_variation_id',$variant_id)
        ->groupBy('product_variation_id')
        ->first();


        return $total_quantity;
    }
}
