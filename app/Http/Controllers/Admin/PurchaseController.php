<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\OrderStatus;
use App\Models\ProductVariant;
use App\Models\PaymentMethod;
use App\Models\ProductVariantVendor;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\Prefix;
use App\Models\PaymentHistory;
use App\Models\Tax;
use App\Models\PaymentTerm;
use App\Models\UserCompanyDetails;
use App\Models\PurchaseStockHistory;
use App\Models\PurchseAttachments;
use Illuminate\Http\Request;
use App\User;
use Session;
use Redirect;
use Response;
use Auth;
use DB;
use PDF;
use Str;
use App\Models\Employee;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('purchase','read')) {
                abort(404);
            }
        }
      $data = array();
      $orders = array();
      $purchases = Purchase::orderBy('id','DESC')->get();
      foreach ($purchases as $key => $purchase) {
        $paid_amount=PaymentHistory::where(['ref_id'=> $purchase->id,'payment_from'=> 1])->sum('amount');
        $vendor_name      = Vendor::find($purchase->vendor_id)->name;
        $product_details  = PurchaseProducts::select(DB::raw('sum(quantity) as quantity'),
                                DB::raw('sum(sub_total) as sub_total'))->where('purchase_id',$purchase->id)->first();
        $order_status     = DB::table('order_status')->where('id',$purchase->purchase_status)->first();

        if($purchase->payment_status==1){
          $payment_status = 'Paid';
        }
        elseif($purchase->payment_status==2){
          $payment_status = 'Not Paid';
        }
        else{
          $payment_status = 'Partly Paid';
        }
        $orders[] = [
          'purchase_date'    => $purchase->purchase_date,
          'purchase_id'      => $purchase->id,
          'vendor'           => $vendor_name,
          'po_number'        => $purchase->purchase_order_number,
          'quantity'         => $product_details->quantity,
          'grand_total'      => $product_details->sub_total,
          'amount'           => $purchase->amount,
          'balance'          => ($product_details->sub_total)-($paid_amount),
          'payment_status'   => $payment_status,
          'p_status'         => $purchase->payment_status,
          'order_status'     => $order_status->status_name,
          'color_code'       => $order_status->color_codes,
          'status_id'        => $purchase->purchase_status,
          'sgd_total_amount' => $purchase->sgd_total_amount
        ];
      }
      $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)->pluck('payment_method','id')
                                    ->toArray();
      $data['orders']         = $orders;
      return view('admin.purchase.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('purchase','create')) {
                abort(404);
            }
        }
      $data=array();
      $data['vendors']        = [''=>'Please Select']+Vendor::where('is_deleted',0)->where('status',1)
                                    ->pluck('name','id')->toArray();
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

      return view('admin.purchase.create',$data);
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
        'purchase_date'         => 'required',
        'purchase_order_number' => 'required',
        'purchase_status'       => 'required',
        'vendor_id'             => 'required',
        'payment_status'        => 'required',
      ],
      [
        'vendor_id.required'    => 'The vendor field is required.'
      ]);
       if (!Auth::check() && Auth::guard('employee')->check()) {
          $created_user_type=2;
          $auth_id=Auth::guard('employee')->user()->id;
       }
       else{
          $created_user_type=2;
          $auth_id=Auth::id();
       }
      $purchase_data=[
        'purchase_date'         => date('Y-m-d H:i:s'),
        'purchase_order_number' => $request->purchase_order_number,
        'purchase_status'       => $request->purchase_status,
        'vendor_id'             => $request->vendor_id,
        'order_tax'             => $request->order_tax,
        'order_discount'        => $request->order_discount,
        'payment_term'          => $request->payment_term,
        'payment_status'        => $request->payment_status,
        'payment_reference_no'  => $request->payment_reference_no,
        'amount'                => $request->amount,
        'paying_by'             => $request->paying_by,
        'payment_note'          => $request->payment_note,
        'note'                  => $request->note,
        'order_tax_amount'      => $request->order_tax_amount,
        'total_amount'          => $request->total_amount,
        'sgd_total_amount'      => $request->sgd_total_amount,
        'user_id'               => $auth_id,
        'created_user_type'     => $created_user_type,
        'created_at'            => date('Y-m-d H:i:s')
       ];

      $purchase_id = Purchase::insertGetId($purchase_data);

      $product_ids           = $request->variant['product_id'];
      $variant               = $request->variant;
      $product_id            = $variant['product_id'];
      $stock_qty             = $variant['stock_qty'];
      $base_price            = $variant['base_price'];
      $retail_price          = $variant['retail_price'];
      $minimum_selling_price = $variant['minimum_selling_price'];
      $sub_total             = $variant['sub_total'];
      $variant_id            = $variant['variant_id'];

      foreach ($product_ids as $key => $variant) {
        if ($stock_qty[$key]!=0) {
          $data=[
            'purchase_id'           => $purchase_id,
            'product_id'            => $product_id[$key],
            'product_variation_id'  => $variant_id[$key],
            'base_price'            => $base_price[$key],
            'retail_price'          => $retail_price[$key],
            'minimum_selling_price' => $minimum_selling_price[$key],
            'quantity'              => $stock_qty[$key],
            'discount'              => 0,
            'product_tax'           => 0,
            'sub_total'             => $sub_total[$key],
          ];
          $purchase_product_id=DB::table('purchase_products')->insertGetId($data);
            PurchaseStockHistory::insert([
              'purchase_id'             => $purchase_id,
              'purchase_product_id'     => $purchase_product_id,
              'qty_received'            => $stock_qty[$key],
              'damage_quantity'         => 0,
              'missed_quantity'         => 0,
              'stock_quantity'          => 0,
              'is_primary'              => 1,
              'created_at'              => date('Y-m-d H:i:s'),
            ]);

        }
      }

      if ($request->amount!="") {
        $data=[
          'ref_id'          => $purchase_id,
          'reference_no'    => $request->payment_reference_no,
          'payment_from'    => 1,
          'amount'          => $request->amount,
          'payment_notes'   => $request->payment_note,
          'created_at'      => date('Y-m-d H:i:s'),
          'payment_id'      => $request->paying_by,
        ];
        PaymentHistory::insert($data);
        $total_amount=$request->total_payment;
        $total_paid=$request->amount;
        $balance_amount=$total_amount-$total_paid;
        if ($balance_amount==0) 
          $payment_status=1;
        else
          $payment_status=2; 

        Purchase::where('id',$request->id)->update(['payment_status'=>$payment_status]);
      }

      if (!$request->has('from')) {
        return Redirect::route('purchase.index')->with('success','Purchase order created successfully...!');  
      }
      return Redirect::route('low-stocks.index')->with('success','Purchase order created successfully...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('purchase','read')) {
                abort(404);
            }
        }
      $data=array();
      $purchase               = Purchase::find($purchase->id);
      $data['purchase']       = $purchase;
      $data['admin_address']  = UserCompanyDetails::where('customer_id',1)->first();
      $data['vendor_address'] = Vendor::where('id',$purchase->vendor_id)->first();
      $data['customer_address'] = User::with('address')->where('id',$purchase->user_id)->first();
      $products = PurchaseProducts::where('purchase_id',$purchase->id)->groupBy('product_id')->get();

      if ($purchase->created_user_type==2) {
        $creater_name=Employee::where('id',$purchase->user_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$purchase->user_id)->first();
        $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
      }

      $data['creater_name']=$creater_name;
      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = PurchaseProducts::where('purchase_id',$purchase->id)->where('product_id',$product->product_id)
                            ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'purchase_id'     => $purchase->id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
      $data['purchase_products'] = $product_data;
      $data['product_name']      = $product_name;
      return view('admin.purchase.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('purchase','update')) {
                abort(404);
            }
        }
      $data=array();


      $data['order_status']   = [''=>'Please Select']+OrderStatus::where('status',1)->whereIn('id',[1,2,8])
                                    ->pluck('status_name','id')->toArray();
      $data['payment_method'] = PaymentMethod::where('status',1)->pluck('payment_method','id')->toArray();
      $data['payment_terms']  = [''=>'Please Select']+PaymentTerm::where('published',1)->where('is_deleted',0)
                                    ->pluck('name','id')->toArray();
      $data['taxes']          = Tax::where('published',1)->where('is_deleted',0)->get();

      $products = PurchaseProducts::where('purchase_id',$purchase->id)->groupBy('product_id')->get();
      $product_data = $product_variant=$all_product_ids = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = PurchaseProducts::where('purchase_id',$purchase->id)->where('product_id',$product->product_id)
                              ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'purchase_id'     => $purchase->id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
        $all_product_ids[]=$product->product_id;
      }
      $all_vendors=DB::table('product_variant_vendors as pvv')
                   ->leftjoin('vendors as v','v.id','pvv.vendor_id')
                   ->where('status',1)
                   ->whereIn('product_id',$all_product_ids)
                   ->pluck('name','v.id')->toArray();

      $data['vendors']        = [''=>'Please Select']+$all_vendors;
      $data['purchase_products'] = $product_data;
      $data['purchase']          = Purchase::find($purchase->id);
      $data['product_name']      = $product_name;
      return view('admin.purchase.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {

      $purchase_id = $purchase->id;
      $this->validate(request(),[
        'purchase_date'         => 'required',
        'purchase_order_number' => 'required',
        'purchase_status'       => 'required',
        'vendor_id'             => 'required',
        'payment_status'        => 'required'
      ]);

      $new_variant=$request->new_variant;
      $variant=$request->variant;

      $existing_product_id=$new_product_variant=array();
      if (isset($variant['product_id'])) {
        $existing_product_id=array_unique($variant['product_id']);
      }
      if (isset($new_variant['product_id'])) {
        $new_product_variant=array_unique($new_variant['product_id']);
      }
      $active_product_ids=array_merge($existing_product_id,$new_product_variant);

      $deleted_products=PurchaseProducts::whereNotIn('product_id',$active_product_ids)
                        ->pluck('product_id')
                        ->toArray();
      if (isset($deleted_products)) {
        PurchaseProducts::whereIn('product_id',array_unique($deleted_products))->delete();
      }

      $purchase_data=[
        'purchase_order_number' => $request->purchase_order_number,
        'purchase_status'       => $request->purchase_status,
        'vendor_id'             => $request->vendor_id,
        'order_tax'             => $request->order_tax,
        'order_discount'        => $request->order_discount,
        'payment_term'          => $request->payment_term,
        'payment_status'        => $request->payment_status,
        'payment_reference_no'  => $request->payment_reference_no,
        'amount'                => $request->amount,
        'paying_by'             => $request->paying_by,
        'payment_note'          => $request->payment_note,
        'note'                  => $request->note,
        'order_tax_amount'      => $request->order_tax_amount,
        'total_amount'          => $request->total_amount,
        'sgd_total_amount'      => $request->sgd_total_amount,
      ];

      Purchase::where('id',$purchase_id)->update($purchase_data);


        
      // $product_ids=$request->variant['product_id'];
      $variant=$request->variant;
      $stock_qty=$variant['stock_qty'];
      $sub_total=$variant['sub_total'];
      $row_ids=$variant['row_id'];
      foreach ($row_ids as $key => $row_id) {
        $data=[
          'quantity'                  => $stock_qty[$key],
          'sub_total'                 => $sub_total[$key],
        ];
        PurchaseProducts::where('id',$row_id)->update($data);
      }

      if ($request->has('new_variant')) {
        $product_ids           = $request->new_variant['product_id'];
        $variant               = $request->new_variant;
        $product_id            = $variant['product_id'];
        $stock_qty             = $variant['stock_qty'];
        $base_price            = $variant['base_price'];
        $retail_price          = $variant['retail_price'];
        $minimum_selling_price = $variant['minimum_selling_price'];
        $sub_total             = $variant['sub_total'];
        $variant_id            = $variant['variant_id'];

        foreach ($product_ids as $key => $variant) {
          if ($stock_qty[$key]!=0) {
            $data=[
              'purchase_id'           => $purchase_id,
              'product_id'            => $product_id[$key],
              'product_variation_id'  => $variant_id[$key],
              'base_price'            => $base_price[$key],
              'retail_price'          => $retail_price[$key],
              'minimum_selling_price' => $minimum_selling_price[$key],
              'quantity'              => $stock_qty[$key],
              'discount'              => 0,
              'product_tax'           => 0,
              'sub_total'             => $sub_total[$key],
            ];
            DB::table('purchase_products')->insert($data);
          }
        }
      }



      return Redirect::route('purchase.index')->with('success','Purchase order created successfully...!');        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Purchase $purchase)
    {
      Purchase::where('id',$purchase->id)->delete();
      PurchaseProducts::where('purchase_id',$purchase->id)->delete();

      if ($request->ajax())  return ['status'=>true];
      else return Redirect::route('purchase.index')->with('success','Purchase order deleted successfully...!');

    }
    public function ProductSearch(Request $request)
    {

        $search_type=$request->product_search_type;
        $product_id=$request->product_id;
        $from=$request->from;
        $data=$options=array();

        if ($search_type=="options") {
            $options=$this->Options($product_id);
            $data['product_variant']=$this->Variants($product_id);
            
            $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('name','asc')->get();
            $data['options'] = $options['options'];
            $data['options_json'] = Response::json($options['options']);

            $data['option_count'] = $options['option_count'];
            $data['product_id'] = $product_id;
            $data['product_name']=Product::where('id',$product_id)->value('name');
            if ($from=="edit") {
                $view=view('admin.purchase.edit_variations',$data)->render();
            }
            else{
               $view=view('admin.purchase.variations',$data)->render();
            }

           return $view;
        }
        elseif ($search_type=="header") {
            $data['product_id'] = $product_id;
            $data['product_variant']=$this->Variants($product_id);
            $options=$this->Options($product_id);
           $data['options'] = $options['options'];
            $data['options_json'] = Response::json($options['options']);

            $data['option_count'] = $options['option_count'];

           return Response::json($data);
        }
        elseif ($search_type=="product") {

            $product_names=Product::leftjoin('product_variant_vendors as pvv','pvv.product_id','products.id')
                          ->where("name","LIKE","%".$request->input('name')."%")
                          ->where('is_deleted',0)
                          ->where('published',1);
                          if ($request->vendor_id!="") {
                            $product_names=$product_names->where('vendor_id',$request->vendor_id);
                          }
                          $product_names=$product_names->pluck('name','products.id')->toArray();
            $names=array();
            foreach ($product_names as $key => $name) {
                $names[]=[
                    'value'=>$key,
                    'label'  => $name
                ];
            }  
        }
        return response()->json($names);
    }
    public function FindVendors(Request $request)
    {
      $product_id=$request->product_id;
      $product_variant=DB::table('product_variant_vendors as pvv')
                       ->leftjoin('vendors as v','pvv.vendor_id','v.id')
                       ->where('pvv.product_id',$product_id)
                       ->pluck('name','v.id')
                       ->toArray();

      return ['products'=>$product_variant];
    }
    public function Variants($product_id,$variation_id=0)
    {

        $variant = ProductVariant::where('product_id',$product_id)->where('disabled',0)->where('is_deleted',0)->first();

        if ($variation_id!=0) {

             $productVariants = ProductVariant::where('product_id',$product_id)
                                  ->where('is_deleted',0)
                                  ->whereIn('id',$variation_id)
                                  ->where('disabled',0)
                                  ->get();
        }
        else{
            $productVariants = ProductVariant::where('product_id',$product_id)
                               ->where('is_deleted',0)
                               ->where('disabled',0)
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
    public function GetOptionsName($option_id)
    {
        $option_name=DB::table('product_options')
                     ->where('id',$optoin_name)
                     ->where('published',1)
                     ->value('option_name');
        return $option_name;
    }
    public function GetOptionValueName($option_value_id)
    {
        $option_value_name=DB::table('product_option_values')
                           ->where('id',$option_value_id)
                           ->where('is_deleted',0)
                           ->value('option_value');

        return $option_value_name;
    }
    public function CreatePurchasePayment(Request $request)
    {
      $order_paid_total=PaymentHistory::where('ref_id',$request->id)->where('payment_from',1)->sum('amount');
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

        $total_paid_amount=PaymentHistory::where('ref_id',$request->id)->where('payment_from',1)->sum('amount');

        $total_payment=Purchase::where('id',$request->id)->value('sgd_total_amount');

     /*   $balance_amount=$total_paid_amount+$total_payment;
        $balance_amount=$balance_amount-$total_payment;*/

        $total_amount=$total_payment;
        $total_paid=$request->amount;

        $balance_amount=$order_paid_total+$total_paid;

        $balance_amount=$balance_amount-$total_amount;

        if ($balance_amount==0) 
          $payment_status=1;
        else
          $payment_status=2; 

        // dd($balance_amount,$payment_status);

        Purchase::where('id',$request->id)->update(['payment_status'=>$payment_status]);
        return Redirect::back()->with('success','Payment added successfully...!');
    }

    public function ViewPurchasePayment($purchase_id)
    {
        $all_payment_history=PaymentHistory::with('PaymentMethod')
                             ->where('ref_id',$purchase_id)
                             ->where('payment_from',1)
                             ->get()
                             ->toArray();

        return $all_payment_history;
    }
    public function PurchaseHistory(Request $request)
    {
      $data=array();
      $purchase_id=$request->purchase_id;
          $products = PurchaseProducts::where('purchase_id',$purchase_id)->groupBy('product_id')->get();

      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = PurchaseProducts::where('purchase_id',$purchase_id)->where('product_id',$product->product_id)
                            ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'purchase_id'     => $purchase_id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
      $data['purchase_products'] = $product_data;
      $purchase               = Purchase::find($purchase_id);
      $data['purchase']       = $purchase;

      $view=view('admin.purchase.show_products',$data)->render();

      return $view;
    }
    public function PurchasePDF($purchase_id)
    {
      $data=array();
      $purchase               = Purchase::find($purchase_id);
      $purchase       = $purchase;
      $admin_address  = UserCompanyDetails::where('customer_id',1)->first();
      $vendor_address = Vendor::where('id',$purchase->vendor_id)->first();
      $customer_address = User::with('address')->where('id',$purchase->user_id)->first();
      $products = PurchaseProducts::where('purchase_id',$purchase_id)->groupBy('product_id')->get();
      if ($purchase->created_user_type==2) {
        $creater_name=Employee::where('id',$purchase->user_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$purchase->user_id)->first();
        $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
      }
      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = PurchaseProducts::where('purchase_id',$purchase_id)->where('product_id',$product->product_id)
                            ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'purchase_id'     => $purchase_id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
      $purchase_products = $product_data;
      $product_name      = $product_name;
      //return view('admin.purchase.purchase_pdf',compact('purchase','purchase_products','admin_address','vendor_address'));

      $layout = View::make('admin.purchase.purchase_pdf',compact('purchase','purchase_products','admin_address','vendor_address','creater_name'));
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML($layout->render());
      return $pdf->download('Purchase-'.$purchase->purchase_order_number.'.pdf');
     
    }
    public function PurchasePrint($purchase_id)
    {
      $data=array();
      $purchase               = Purchase::find($purchase_id);
      $purchase       = $purchase;
      $admin_address  = UserCompanyDetails::where('customer_id',1)->first();
      $vendor_address = Vendor::where('id',$purchase->vendor_id)->first();
      $customer_address = User::with('address')->where('id',$purchase->user_id)->first();
      $products = PurchaseProducts::where('purchase_id',$purchase_id)->groupBy('product_id')->get();
      if ($purchase->created_user_type==2) {
        $creater_name=Employee::where('id',$purchase->user_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$purchase->user_id)->first();
        $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
      }

      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = PurchaseProducts::where('purchase_id',$purchase_id)->where('product_id',$product->product_id)
                            ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);

        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'purchase_id'     => $purchase_id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
      $purchase_products = $product_data;
      $product_name      = $product_name;
      return view('admin.purchase.purchase_print',compact('purchase','purchase_products','admin_address','vendor_address','creater_name'));

     
    }

    public function ViewPurchaseAttachments($purchase_id)
    {
        $data=array();
        $data['purchase_attachments']=PurchseAttachments::where('purchase_id',$purchase_id)->get();
        return view('admin.purchase.view_attachments',$data);

    }
    public function AddAttachments(Request $request)
    {
      $purchase_id=$request->id;
      $comments=$request->purchase_comments;
      if ($request->hasFile('attachments')) {
        $image = $request->file('attachments');
        $image_name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/theme/images/purchase_attachment');
        $image->move($destinationPath, $image_name);
      }

      $attachments=new PurchseAttachments();
      $attachments->attachment=$image_name;
      $attachments->purchase_id=$purchase_id;
      $attachments->comments=$comments;
      $attachments->created_at=date('Y-m-d H:i:s');
      $attachments->save();

      $purchase_no=Purchase::where('id',$purchase_id)->value('purchase_order_number');
      return Redirect::back()->with('success','Attachment successfully adedd to '.$purchase_no.'...!');

    }
    public function DownloadPurchaseAttachment($attachment_id)
    {
      $attachment=PurchseAttachments::where('id',$attachment_id)->value('attachment');
      $path=public_path('/theme/images/purchase_attachment/').$attachment;
      
      return Response::download($path, $attachment);
    }
}
