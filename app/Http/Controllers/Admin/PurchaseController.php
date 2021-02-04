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
use App\Models\Settings;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Response;
use DB;
class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $purchases=Purchase::orderBy('id','DESC')->get();
        $data=array();
        $orders=array();
        foreach ($purchases as $key => $purchase) {
            $vendor_name=Vendor::find($purchase->vendor_id)->name;
            $product_details=PurchaseProducts::select(DB::raw('sum(quantity) as quantity'),DB::raw('sum(sub_total) as sub_total'))
                ->where('purchase_id',$purchase->id)
                ->first();

            $orders[]=[
                'purchase_date'=>$purchase->purchase_date,
                'purchase_id'=>$purchase->id,
                'vendor'   =>$vendor_name,
                'po_number'=>$purchase->purchase_order_number,
                'quantity' => $product_details->quantity,
                'grand_total' => $product_details->sub_total,
                'amount' => $purchase->amount,
                'balance' => ($product_details->sub_total)-($purchase->amount),
                'payment_status' => ($purchase->payment_status==1)?'Paid':'Not Paid'
            ];
        }

        $data['orders']=$orders;
        return view('admin.purchase.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=array();

        $order_status=OrderStatus::where('status',1)
                              ->pluck('status_name','id')
                              ->toArray();

        $payment_method=PaymentMethod::where('status',1)
                              ->pluck('payment_method','id')
                              ->toArray();
        $vendors=Vendor::where('is_deleted',0)
                         ->where('status',1)
                         ->pluck('name','id')
                         ->toArray();
        $data['vendors']=[''=>'Please Select']+$vendors;

        $data['order_status']=[''=>'Please Select']+$order_status;
        $data['payment_method']=[''=>'Please Select']+$payment_method;

        $order_codee=Settings::where('key','prefix')
                         ->where('code','purchase_no')
                        ->value('content');

        if (isset($order_codee)) {
            $value=unserialize($order_codee);

            $char_val=$value['value'];
            $explode_val=explode('-',$value['value']);
            $total_datas=Purchase::count();
            $total_datas=($total_datas==0)?end($explode_val)+1:$total_datas+1;
            $data_original=$char_val;

            $search=['[dd]', '[mm]', '[yyyy]', end($explode_val)];
            $replace=[date('d'), date('m'), date('Y'), $total_datas+1 ];
            $data['purchase_code']=str_replace($search,$replace, $data_original);
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
            'purchase_date' => 'required',
            'purchase_order_number' => 'required',
            'purchase_status' => 'required',
            'vendor_id' => 'required',
            'payment_status' => 'required',
       ],
       [
            'vendor_id.required'    => 'The vendor field is required.'
       ]

   );

       $purchase_data=[
            'purchase_date' =>date('Y-m-d H:i:s'),
            'purchase_order_number' =>$request->purchase_order_number,
            'purchase_status' =>$request->purchase_status,
            'vendor_id' =>$request->vendor_id,
            'order_tax' =>$request->order_tax,
            'order_discount' =>$request->order_discount,
            'payment_term' =>$request->payment_term,
            'payment_status' =>$request->payment_status,
            'payment_reference_no' =>$request->payment_reference_no,
            'amount' =>$request->amount,
            'paying_by' =>$request->paying_by,
            'payment_note' =>$request->payment_note,
            'note' =>$request->note,
       ];

       $purchase_id=Purchase::insertGetId($purchase_data);

       $product_ids=$request->variant['product_id'];
       $variant=$request->variant;
       $product_id=$variant['product_id'];
       $stock_qty=$variant['stock_qty'];

       $base_price=$variant['base_price'];
       $retail_price=$variant['retail_price'];
       $minimum_selling_price=$variant['minimum_selling_price'];
       $sub_total=$variant['sub_total'];
       $variant_id=$variant['variant_id'];

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

      return Redirect::route('purchase.index')->with('success','Purchase order created successfully...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        $data=array();

        $order_status=OrderStatus::where('status',1)
                              ->pluck('status_name','id')
                              ->toArray();

        $payment_method=PaymentMethod::where('status',1)
                              ->pluck('payment_method','id')
                              ->toArray();
        $vendors=Vendor::where('is_deleted',0)
                         ->where('status',1)
                         ->pluck('name','id')
                         ->toArray();
        $data['vendors']=[''=>'Please Select']+$vendors;

        $data['order_status']=[''=>'Please Select']+$order_status;
        $data['payment_method']=[''=>'Please Select']+$payment_method;



        $products=PurchaseProducts::where('purchase_id',$purchase->id)->groupBy('product_id')->get();

            $product_data=$product_variant=array();
            foreach ($products as $key => $product) {
                $product_name=Product::where('id',$product->product_id)
                              ->value('name');
                $options=$this->Options($product->product_id);

                $all_variants=PurchaseProducts::where('purchase_id',$purchase->id)
                              ->where('product_id',$product->product_id)
                              ->pluck('product_variation_id')
                              ->toArray();

                $product_variant=$this->Variants($product->product_id,$all_variants);
                $product_data[$product->product_id]=[
                    'row_id'         => $product->id,
                    'purchase_id'    => $purchase->id,
                    'product_id'=> $product->product_id,
                    'product_name'  => $product_name,
                    'options'       => $options['options'],
                    'option_count'  => $options['option_count'],
                    'product_variant'  => $product_variant
                ];
            }
            $data['purchase_products']=$product_data;


        $data['purchase']=Purchase::find($purchase->id);
        $data['product_name']=$product_name;

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
       $purchase_id=$purchase->id;
       $this->validate(request(),[
            'purchase_date' => 'required',
            'purchase_order_number' => 'required',
            'purchase_status' => 'required',
            'vendor_id' => 'required',
            'payment_status' => 'required',
       ]);

       $purchase_data=[
            // 'purchase_date' =>date('y-m-d H:i:s'),
            'purchase_order_number' =>$request->purchase_order_number,
            'purchase_status' =>$request->purchase_status,
            'vendor_id' =>$request->vendor_id,
            'order_tax' =>$request->order_tax,
            'order_discount' =>$request->order_discount,
            'payment_term' =>$request->payment_term,
            'payment_status' =>$request->payment_status,
            'payment_reference_no' =>$request->payment_reference_no,
            'amount' =>$request->amount,
            'paying_by' =>$request->paying_by,
            'payment_note' =>$request->payment_note,
            'note' =>$request->note,
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

     /*  if (isset($variant['option_id1'])) {
           $option_id1=$variant['option_id1'];
           $option_value_id1=$variant['option_value_id1'];
       }
       if(isset($variant['option_id2'])){
           $option_id2=$variant['option_id2'];
           $option_value_id2=$variant['option_value_id2'];
       }

       if (isset($variant['option_id3'])) {
           $option_id3=$variant['option_id3'];
           $option_value_id3=$variant['option_value_id3'];
       }elseif (isset($variant['option_id4'])) {
           $option_id4=$variant['option_id4'];
           $option_value_id4=$variant['option_value_id4'];
       }
       elseif (isset($variant['option_id5'])) {
           $option_id5=$variant['option_id5'];
           $option_value_id5=$variant['option_value_id5'];
       }
       foreach ($product_ids as $key => $variant) {
            $data[]=[
                'purchase_id'   => $purchase_id,
                'product_id'    => $product_id[$key],
                'quantity'    => $stock_qty[$key],
                'base_price'    => $base_price[$key],
                'retail_price'    => $retail_price[$key],
                'discount'    => 0,
                'product_tax'    => 0,
                'sub_total'    => $sub_total[$key],
                'minimum_selling_price'    => $minimum_selling_price[$key],

                'option_id'     => isset($option_id1[$key])?$option_id1[$key]:null,
                'option_value_id' => isset( $option_value_id1[$key])? $option_value_id1[$key]:null,

                'option_id2' => isset( $option_id2[$key])? $option_id2[$key]:null,
                'option_value_id2' => isset($option_value_id2[$key])?$option_value_id2[$key]:null,

                'option_id3' => isset($option_id3[$key])?$option_id3[$key]:null,
                'option_value_id3' => isset($option_value_id3[$key])?$option_value_id3[$key]:null,

                'option_id4' => isset($option_id4[$key])?$option_id4[$key][$key]:null,
                'option_value_id4' => isset($option_value_id4[$key])?$variant['option_value_id4'][$key]:null,

                'option_id5' => isset( $option_id5[$key])? $option_id5[$key]:null,
                'option_value_id5' => isset($option_value_id5[$key])?$$option_value_id5[$key]:null,
            ];
           
       }

       DB::table('purchase_products')->insert($data);*/

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

           $view=view('admin.purchase.variations',$data)->render();

           return $view;
        }
        elseif ($search_type=="header") {
            $data['product_id'] = $product_id;
            $data['product_variant']=$this->Variants($product_id);
            $options=$this->Options($product_id);
           $data['options'] = $options['options'];
            $data['options_json'] = Response::json($options['options']);

            $data['option_count'] = $options['option_count'];

           // $data['view']=view('admin.purchase.variations',$data)->render();
           return Response::json($data);
        }
        elseif ($search_type=="product") {

            $product_names=Product::where("name","LIKE","%".$request->input('name')."%")
                          ->pluck('name','id')
                          ->toArray();
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
    public function Variants($product_id,$variation_id)
    {

        $variant = ProductVariant::where('product_id',$product_id)->where('is_deleted',0)->first();

        if (isset($variation_id)) {

             $productVariants = ProductVariant::where('product_id',$product_id)
                            ->where('is_deleted',0)
                            ->whereIn('id',$variation_id)
                            ->get();
        }
        else{
            $productVariants = ProductVariant::where('product_id',$product_id)
                               ->where('is_deleted',0)
                               ->get();
        }

        $product_variants = array();
        foreach ($productVariants as $key => $variants) {
            
            $variant_details = ProductVariantVendor::where('product_id',$variants->product_id)->where('product_variant_id',$variants->id)->first();

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
        $variant = ProductVariant::where('product_id',$id)->where('is_deleted',0)->first();

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
                     ->value('option_name');
        return $option_name;
    }
    public function GetOptionValueName($option_value_id)
    {
        $option_value_name=DB::table('product_option_values')
                           ->where('id',$option_value_id)
                           ->value('option_value');

        return $option_value_name;
    }
}
