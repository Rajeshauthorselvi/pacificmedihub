<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RFQ;
use App\Models\RFQProducts;
use App\User;
use App\Models\UserAddress;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Vendor;
use App\Models\Employee;
use App\Models\Prefix;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\PaymentTerm;
use App\Models\Tax;
use App\Models\Currency;
use App\Models\RFQComments;
use App\Models\RFQCommentsAttachments;
use App\Models\Orders;
use App\Models\Notification;
use App\Models\DeliveryMethod;
use Auth;
use Redirect;
use Session;
use PDF;
use Str;
use Response;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class RFQController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('rfq','read')) {
                abort(404);
            }
        }
      $data=array();

     
    $rfqs=RFQ::with('customer','salesrep','statusName');

    if (!Auth::check() && Auth::guard('employee')->check() && Auth::guard('employee')->user()->emp_department==1) {
        $rfqs->where('sales_rep_id',Auth::guard('employee')->user()->id);
    }
     $rfqs=$rfqs->orderBy('rfq.id','desc')->get();

     $data['rfqs']=$rfqs;

      return view('admin.rfq.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('rfq','create')) {
                abort(404);
            }
        }
      $data=array();
      $data['customers']      = User::where('is_deleted',0)->where('status',1)->where('role_id',7)->get()->toArray();
      $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
      $data['order_status']   = OrderStatus::where('status',1)->whereIn('id',[25,21])->pluck('status_name','id')->toArray();

      $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)->pluck('payment_method','id')
                                  ->toArray();
      $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();
      $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                  ->pluck('name','id')->toArray();
      $data['currencies']     = Currency::where('is_deleted',0)->where('published',1)->get();
      $data['delivery_methods'] = DeliveryMethod::all();
      $data['free_delivery']    = DeliveryMethod::where('is_free_delivery','yes')->where('status',1)->value('amount');
      $data['free_delivery_target'] = DeliveryMethod::where('is_free_delivery','yes')->where('status',1)->value('target_amount');

      $data['rfq_id']='';
      $rfq_code = Prefix::where('key','prefix')->where('code','rfq')->value('content');
      if (isset($rfq_code)) {
        $value = unserialize($rfq_code);
        $char_val = $value['value'];
        $year = date('Y');
        $total_datas = RFQ::count();
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
        $data['rfq_id']=$replace_number;
      }

      return view('admin.rfq.create',$data);
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
        'order_no'  =>'required',
        'status'=>'required',
        'customer_id'=>'required',
        'sales_rep_id'=>'required'
      ]);
       if (!Auth::check() && Auth::guard('employee')->check()) {
          $created_user_type=2;
          $auth_id=Auth::guard('employee')->user()->id;
       }
       else{
          $created_user_type=1;
          $auth_id=Auth::id();
       }
      $rfq_details=[
        'order_no'              => $request->order_no,
        'status'                => $request->status,
        'customer_id'           => $request->customer_id,
        'sales_rep_id'          => $request->sales_rep_id,
        'currency'              => $request->currency,
        'order_tax'             => $request->order_tax,
        'order_discount'        => $request->order_discount,
        'delivery_method_id'    => $request->delivery_method_id,
        'delivery_charge'       => $request->delivery_charge,
        'payment_term'          => $request->payment_term,
        'order_tax_amount'      => $request->order_tax_amount,
        'total_amount'          => $request->total_amount,
        'sgd_total_amount'      => $request->sgd_total_amount,
        'exchange_total_amount' => $request->exchange_rate,
        'notes'                 => $request->notes,
        'user_id'               => $auth_id,
        'delivery_address_id'   => $request->del_add_id,
        'created_user_type'     => $created_user_type,
        'created_at'            => date('Y-m-d H:i:s')
      ];
      $rfq_id=RFQ::insertGetId($rfq_details);

      $quantites             = $request->quantity;
      $variant               = $request->variant;
      $product_ids           = $variant['product_id'];
      $variant_id            = $variant['id'];
      $base_price            = $variant['base_price'];
      $retail_price          = $variant['retail_price'];
      $minimum_selling_price = $variant['minimum_selling_price'];
      $stock_qty             = $variant['stock_qty'];
      $rfq_price             = $variant['rfq_price'];
      $sub_total             = $variant['sub_total'];
      $last_rfq_price        = $variant['last_rfq_price'];

      foreach ($product_ids as $key => $product_id) {
        if ($stock_qty[$key]!=0 && $stock_qty[$key]!="") {
          $data=[
            'rfq_id'                    => $rfq_id,
            'product_id'                => $product_id,
            'product_variant_id'        => $variant_id[$key],
            'base_price'                => $base_price[$key],
            'retail_price'              => $retail_price[$key],
            'minimum_selling_price'     => $minimum_selling_price[$key],
            'quantity'                  => $stock_qty[$key],
            'rfq_price'                 => $rfq_price[$key],
            'sub_total'                 => $sub_total[$key],
            'last_rfq_price'            => $last_rfq_price[$key]
          ];
          RFQProducts::insert($data);
        }
      }
      return Redirect::route('rfq.index')->with('success','RFQ added successfully...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('rfq','read')) {
                abort(404);
            }
        }

      $data = array();
      $products = RFQProducts::where('rfq_id',$id)->groupBy('product_id')->get();
      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $all_variants    = RFQProducts::where('rfq_id',$id)->where('product_id',$product->product_id)
                            ->pluck('product_variant_id')->toArray();
        $options         = $this->Options($product->product_id);
        $product_variant = $this->Variants($product->product_id,$all_variants);
        $check_rfq_price_exists=RFQProducts::where('rfq_id',$id)
                                ->where('product_id',$product->product_id)
                                ->whereNotNull('last_rfq_price')
                                ->exists();
        $product_data[$product->product_id]=[
          'rfq_id'          => $id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant,
          'check_rfq_price_exists' => $check_rfq_price_exists
        ];
      }
      $rfq = RFQ::where('id',$id)->first();

      if ($rfq->created_user_type==2) {
        $creater_name=Employee::where('id',$rfq->user_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$rfq->user_id)->first();
        $creater_name=$creater_name->name;
      }
      $data['creater_name']=$creater_name;


      $data['rfqs']             = $rfq;
      $data['admin_address']    = User::where('id',1)->first();
      $data['customer_address'] = UserAddress::where('id',$rfq->delivery_address_id)->first();
      $data['product_datas']    = $product_data;
      $data['rfq_id']           = $id;
      $data['taxes']            = Tax::where('published',1)->where('is_deleted',0)->get();
      $data['payment_terms']    = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                    ->pluck('name','id')->toArray();  
      $data['currencies']       = Currency::where('is_deleted',0)->where('published',1)->get();
      return view('admin.rfq.view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('rfq','update')) {
                abort(404);
            }
        }
      $data=array();
      $data['rfqs']=$rfq_details= RFQ::with('customer','salesrep','statusName')->where('rfq.id',$id)->first();
      $data['order_status']   = OrderStatus::where('status',1)->whereIn('id',[25,21])
                                    ->pluck('status_name','id')->toArray();
      $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)
                                    ->pluck('payment_method','id')->toArray();
      $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)->where('role_id',7)
                                    ->pluck('name','id')->toArray();
      $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                    ->where('role_id',4)->pluck('emp_name','id')->toArray();
      $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();
      $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                    ->pluck('name','id')->toArray();
      $data['currencies']     = Currency::where('is_deleted',0)->where('published',1)->get();
      $data['del_address']    = DB::table('address')
                                     ->where('customer_id',$rfq_details->customer_id)
                                     ->where('is_deleted',0)
                                     ->pluck(DB::raw("CONCAT(name,', ',mobile,', ',address_line1,', ',post_code) as addres"),'id')
                                     ->toArray();
      $product_data = $product_variant=array();
      $products     = RFQProducts::where('rfq_id',$id)->groupBy('product_id')->get();
      $data['delivery_methods'] = DeliveryMethod::all();
        $data['free_delivery'] = DeliveryMethod::where('is_free_delivery','yes')->where('status',1)->value('amount');
        $data['free_delivery_target'] = DeliveryMethod::where('is_free_delivery','yes')->where('status',1)->value('target_amount');
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $all_variants    = RFQProducts::where('rfq_id',$id)->where('product_id',$product->product_id)
                            ->pluck('product_variant_id')->toArray();
        $options         = $this->Options($product->product_id);
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $check_rfq_price_exists=RFQProducts::where('rfq_id',$id)
                                ->where('product_id',$product->product_id)
                                ->whereNotNull('last_rfq_price')
                                ->exists();

        $product_data[$product->product_id] = [
          'rfq_id'          => $id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant,
          'check_rfq_price_exists' => $check_rfq_price_exists
        ];
      }
      $data['total_products']  = RFQProducts::allAmount($id);
      $data['discount_amt']    = isset($rfqs->order_discount)?$rfqs->order_discount:0.00;
      $data['order_tax']       = isset($rfqs->order_tax_amount)?$rfqs->order_tax_amount:0.00;
      $data['delivery_charge'] = $rfq_details->deliveryMethod->amount;
      $data['product_datas']   = $product_data;
      return view('admin.rfq.edit',$data);
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
      $this->validate(request(),[
        'order_no'     => 'required',
        'status'       => 'required',
        'customer_id'  => 'required',
        'sales_rep_id' => 'required'
      ]);
      $variant=$request->variant;
      $new_variant=$request->new_variant;

      $existing_product_id=$new_product_variant=array();
      if (isset($variant['product_id'])) {
        $existing_product_id=array_unique($variant['product_id']);
      }
      if (isset($new_variant['product_id'])) {
        $new_product_variant=array_unique($new_variant['product_id']);
      }
      $active_product_ids=array_merge($existing_product_id,$new_product_variant);
       

      $deleted_products=RFQProducts::where('rfq_id',$id)->whereNotIn('product_id',$active_product_ids)
                        ->pluck('product_id')
                        ->toArray();

      if(isset($deleted_products)) {
        RFQProducts::where('rfq_id',$id)->whereIn('product_id',array_unique($deleted_products))->delete();
      }
      $rfq_details=[
        'order_no'              => $request->order_no,
        'status'                => $request->status,
        'customer_id'           => $request->customer_id,
        'sales_rep_id'          => $request->sales_rep_id,
        'currency'              => $request->currency,
        'order_tax'             => $request->order_tax,
        'order_discount'        => $request->order_discount,
        'delivery_method_id'    => $request->delivery_method_id,
        'delivery_charge'       => $request->delivery_charge,
        'payment_term'          => $request->payment_term,
        'order_tax_amount'      => $request->order_tax_amount,
        'total_amount'          => $request->total_amount,
        'sgd_total_amount'      => $request->sgd_total_amount,
        'exchange_total_amount' => $request->exchange_rate,
        'delivery_address_id'   => $request->del_add_id,
        'notes'                 => $request->notes,
        'updated_at'            => date('Y-m-d H:i:s')
      ];
      RFQ::where('id',$id)->update($rfq_details);

      $variant               = $request->variant;
      $row_ids               = $variant['row_id'];
      $stock_qty             = $variant['stock_qty'];
      $rfq_price             = $variant['rfq_price'];
      $sub_total             = $variant['sub_total'];

      foreach ($row_ids as $key => $row_id) {
        $data=[
          'quantity'   => $stock_qty[$key],
          'rfq_price'  => $rfq_price[$key],
          'sub_total'  => $sub_total[$key],
        ];
        RFQProducts::where('id',$row_id)->update($data);
      }
      if ($request->has('new_variant')) {
          $variant               = $request->new_variant;
          $product_ids           = $variant['product_id'];
          $variant_id            = $variant['id'];
          $base_price            = $variant['base_price'];
          $retail_price          = $variant['retail_price'];
          $minimum_selling_price = $variant['minimum_selling_price'];
          $stock_qty             = $variant['stock_qty'];
          $rfq_price             = $variant['rfq_price'];
          $sub_total             = $variant['sub_total'];

        foreach ($product_ids as $key => $product_id) {
          if ($stock_qty[$key]!=0 && $stock_qty[$key]!="") {
            $data=[
              'rfq_id'                    => $id,
              'product_id'                => $product_id,
              'product_variant_id'        => $variant_id[$key],
              'base_price'                => $base_price[$key],
              'retail_price'              => $retail_price[$key],
              'minimum_selling_price'     => $minimum_selling_price[$key],
              'quantity'                  => $stock_qty[$key],
              'rfq_price'                 => $rfq_price[$key],
              'sub_total'                 => $sub_total[$key]
            ];
            RFQProducts::insert($data);
          }
        }
      }
      return Redirect::route('rfq.index')->with('success','RFQ added successfully.!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $delete_rfq = RFQ::where('id',$id)->delete();
        if($delete_rfq){
          RFQProducts::where('rfq_id',$id)->delete();
        }
        
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('rfq.index')->with('error','RFQ deleted successfully.!');
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
            foreach ($product_names as $key => $name) {
                $names[]=[
                    'value'=>$key,
                    'label'  => $name
                ];
            }  
            return response()->json($names);
        }
        elseif ($search_type=='product_options') {
            $product_id=$request->product_id;
            $options=$this->Options($product_id);
            $data['options'] = $options['options'];
            $data['option_count'] = $options['option_count'];
            $data['product_id'] = $product_id;
            $data['product_name']=Product::where('id',$product_id)->value('name');
            $data['product_variant']=$this->Variants($product_id);

            if ($from=="edit") {
                $view=view('admin.rfq.edit_variants',$data)->render();
            }
            else{
              $view=view('admin.rfq.variants',$data)->render();
            }
            return $view;
        }

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
   public function RFQPDF($id)
    {

      $data = array();
      $products = RFQProducts::where('rfq_id',$id)->groupBy('product_id')->get();
      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $all_variants    = RFQProducts::where('rfq_id',$id)->where('product_id',$product->product_id)
                            ->pluck('product_variant_id')->toArray();
        $options         = $this->Options($product->product_id);
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $product_data[$product->product_id]=[
          'rfq_id'          => $id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
      $rfq = RFQ::where('id',$id)->first();

      if ($rfq->created_user_type==2) {
        $creater_name=Employee::where('id',$rfq->user_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$rfq->user_id)->first();
        $creater_name=$creater_name->name;
      }
      $data['creater_name']=$creater_name;


      $data['rfqs']             = $rfq;
      $data['admin_address']    = User::where('id',1)->first();
      $data['customer_address'] = User::with('address')->where('id',$rfq->customer_id)->first();
      $data['product_datas']    = $product_data;
      $data['rfq_id']           = $id;
      $data['taxes']            = Tax::where('published',1)->where('is_deleted',0)->get();
      $data['payment_terms']    = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                    ->pluck('name','id')->toArray();  
      $data['currencies']       = Currency::where('is_deleted',0)->where('published',1)->get();

      $layout = View::make('admin.rfq.rfq_pdf',$data);
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML($layout->render());
      return $pdf->download('RFQ-'.$rfq->order_no.'.pdf');

    }
    public function RFQComments(Request $request,$rfq_id)
    {
        $data=array();
        $data['rfq_details']=RFQ::with('customer','salesrep','statusName')->where('rfq.id',$rfq_id)->first();

        $data['rfq_id']=$rfq_id;
        $data['comments']=RFQComments::where('rfq_id',$rfq_id)->get();

        if (!Auth::check() && Auth::guard('employee')->check()) {
          return view('admin.rfq.employee_comments',$data);  
        }
        else{
          return view('admin.rfq.admin_comments',$data);  
        }
        
    }
    public function RFQCommentsPost(Request $request)
    {

       if (!Auth::check() && Auth::guard('employee')->check()) {
          $created_user_type=2;
          $auth_id=Auth::guard('employee')->user()->id;
          $creater_name=Auth::guard('employee')->emp_name;
       }
       else{
          $created_user_type=1;
          $auth_id=Auth::id();
          $creater_name=Auth::user()->name;
       }

      $data=[
          'rfq_id'                  => $request->rfq_id,
          'comment'                 => $request->comment,
          'commented_by'            => $auth_id,
          'commented_by_user_type'  => $created_user_type
      ];
      $rfq_commene_id=RFQComments::insertGetId($data);

      $attachments=$request->file('attachment');
      if ($request->hasFile('attachment')) {
        $attachments=$request->file('attachment');
        foreach ($attachments as $key => $attachment) {
            $image = $attachment;
            $image_name = Str::random(8).'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/theme/images/rfq_comment_attachment');
            $image->move($destinationPath, $image_name);
            RFQCommentsAttachments::insert([
              'rfq_comment_id'  => $rfq_commene_id,
              'attachment'      => $image_name
            ]);
        }
      } 
      $rfq_details=RFQ::with('customer','salesrep','statusName')->where('rfq.id',$request->rfq_id)->first();
      Notification::insert([
        'content'             => $creater_name.' added new comment to '.$rfq_details->order_no,
        'url'                 => url('admin/rfq-comments/'.$request->rfq_id),
        'created_at'          => date('Y-m-d H:i:s'),
        'created_by'          => $auth_id,
        'created_user_type'   => $created_user_type,
      ]);

      return Redirect::back();
    }
    public function ViewRFQCommentAttachments($comment_id)
    {
        $all_attachments=RFQCommentsAttachments::where('rfq_comment_id',$comment_id)->pluck('attachment','id');

        return view('admin.rfq.view_attachments',['attachments'=>$all_attachments]);
    }
    public function DownloadRFQCommentAttachments($attachment_id)
    {
      $attachment=RFQCommentsAttachments::where('id',$attachment_id)->value('attachment');
      $path=public_path('/theme/images/rfq_comment_attachment/').$attachment;
      
      return Response::download($path, $attachment);
    }

    public function CheckRfqExistingPrice(Request $request)
    {
      $customer_id=$request->customer_id;
      $product_id=$request->product_id;
      $variant_id=$request->variant_id;
      $product_price=$this->LastRFQPrice($customer_id,$product_id,$variant_id);
      return ['price'=>$product_price];
    }

    public function LastRFQPrice($customer_id,$product_id,$variant_id)
    {
      $product_price=DB::table('orders as o')
                     ->leftjoin('order_products as op','o.id','op.order_id')
                     ->where('o.order_status',13)
                     ->where('o.customer_id',$customer_id)
                      ->where('op.product_id',$product_id)
                      ->where('op.product_variation_id',$variant_id)
                      ->orderBy('op.id','DESC')
                      ->value('final_price');

      return $product_price;
    }
}
