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
use App\User;
use Auth;
use Redirect;
use Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('order','read')) {
                abort(404);
            }
        }
        $data=array();
        $data['orders']=Orders::with('customer','salesrep','statusName')->orderBy('orders.id','desc')->get();
        $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)->pluck('payment_method','id')
                                    ->toArray();
        return view('admin.orders.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('order','create')) {
                abort(404);
            }
        }
        $rfq_id=$request->rfq_id;
        $data=array();
        $data['rfqs']           = RFQ::with('customer','salesrep','statusName')->where('rfq.id',$rfq_id)->first();     
        $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();
        $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',7)->pluck('first_name','id')->toArray();
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',4)->pluck('emp_name','id')->toArray();
        $data['order_status']   = OrderStatus::where('status',1)->whereIn('id',[3,13,11])->pluck('status_name','id')
                                        ->toArray();
        $data['payment_method'] = PaymentMethod::where('status',1)->pluck('payment_method','id')->toArray();
        $data['currencies']     = Currency::where('is_deleted',0)->where('published',1)->get();
        $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();

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

        if($request->order_status==13){
            $order_completed_at = date('Y-m-d H:i:s');
        }else{
            $order_completed_at = NULL;
        }

        $order_data=[
            'rfq_id'                => $request->rfq_id,
            'sales_rep_id'          => $request->sales_rep_id,
            'customer_id'           => $request->customer_id,
            'order_no'              => $request->order_no,
            'order_status'          => $request->order_status,
            'order_tax'             => $request->order_tax,
            'order_discount'        => $request->order_discount,
            'currency'              => $request->currency,
            'payment_term'          => $request->payment_term,
            'payment_status'        => $request->payment_status,
            'order_tax_amount'      => $request->order_tax_amount,
            'total_amount'          => $request->total_amount,
            'sgd_total_amount'      => $request->sgd_total_amount,
            'exchange_total_amount' => $request->exchange_rate,
            'user_id'               => Auth::id(),
            'notes'                 => $request->note,
            'order_completed_at'    => $order_completed_at,
            'created_at'            => date('Y-m-d H:i:s')
       ];
       
        $order_id = Orders::insertGetId($order_data);

        $quantites             = $request->quantity;
        $variant               = $request->variant;
        $product_ids           = $variant['product_id'];
        $variant_id            = $variant['idvariant_id'];
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
       return Redirect::route('orders.index')->with('success','Order created successfully...!');
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
        
        $data['order']            = $orders;
        $data['currencies']       = Currency::where('is_deleted',0)->where('published',1)->get();
        $data['payment_terms']    = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                        ->pluck('name','id')->toArray();
        $data['taxes']            = Tax::where('published',1)->where('is_deleted',0)->get();
        $data['admin_address']    = UserCompanyDetails::where('customer_id',1)->first();
        $data['customer_address'] = User::with('address')->where('id',$orders->customer_id)->first();

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
        return view('admin.orders.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders,$order_id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('order','update')) {
                abort(404);
            }
        }
        $data['order']          = Orders::with('customer','salesrep','statusName')->where('orders.id',$order_id)
                                        ->first();
        $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',7)->pluck('first_name','id')->toArray();
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',4)->pluck('emp_name','id')->toArray();
        $data['order_status']   = OrderStatus::where('status',1)->whereIn('id',[3,13,11])->pluck('status_name','id')
                                        ->toArray();
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
        $data['product_datas']=$product_data;
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
        $this->validate(request(),[
            'order_status'   => 'required',
            'payment_status' => 'required'
        ]);
            
        if($request->order_status==13){
            $order_completed_at = date('Y-m-d H:i:s');
        }else{
            $order_completed_at = NULL;
        }


        $order_data=[
            'sales_rep_id'          => $request->sales_rep_id,
            'customer_id'           => $request->customer_id,
            'order_status'          => $request->order_status,
            'order_tax'             => $request->order_tax,
            'order_discount'        => $request->order_discount,
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


       Orders::where('id',$id)->update($order_data);

       return Redirect::route('orders.index')->with('success','Order details updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $delete_order = Orders::where('id',$id)->delete();
        if($delete_order){
          OrderProducts::where('order_id',$id)->delete();
        }
        
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('orders.index')->with('error','Order deleted successfully.!');
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
            'order_status'          => $rfq->status,
            'order_tax'             => $rfq->order_tax,
            'order_discount'        => $rfq->order_discount,
            'currency'              => $rfq->currency,
            'payment_term'          => $rfq->payment_term,
            'payment_status'        => 3,
            'order_tax_amount'      => $rfq->order_tax_amount,
            'total_amount'          => $rfq->total_amount,
            'sgd_total_amount'      => $rfq->sgd_total_amount,
            'exchange_total_amount' => $rfq->exchange_rate,
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
       return Redirect::route('orders.index')->with('success','Order created successfully...!');
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
            $view=view('admin.orders.variants',$data)->render();
            return $view;
        }

    }

    public function Variants($product_id,$variation_id=0)
    {

        $variant = ProductVariant::where('product_id',$product_id)->where('disabled',0)->where('is_deleted',0)->first();

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
}
