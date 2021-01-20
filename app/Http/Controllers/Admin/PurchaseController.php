<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\OrderStatus;
use App\Models\ProductVariant;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Session;
use Redirect;
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
        $quantity=$request->quantity;
        $subtotal=$request->subtotal;
            foreach ($quantity as $product_id => $option_values) {
                foreach ($option_values as $option_id => $option_values) {
                    foreach ($option_values as $op_value_id => $op_values) {
                        $sub_total=$subtotal[$product_id][$option_id][$op_value_id];
                        $selling_price_details=DB::table('product_variants')
                                               ->where('product_id',$product_id)
                                               ->where('option_id',$option_id)
                                               ->where('option_value_id',$op_value_id)
                                               ->first();
                        $data=[
                            'purchase_id'=> $purchase_id,
                            'product_id'=>$product_id,
                            'option_id'=>$option_id,
                            'option_value_id'=>$op_value_id,
                            'base_price'=> $selling_price_details->base_price,
                            'retail_price'=>$selling_price_details->retail_price,
                            'minimum_selling_price'=>$selling_price_details->minimum_selling_price ,
                            'quantity'=>$op_values,
                            'discount'=>0,
                            'product_tax'=>0,
                            'sub_total'=>$sub_total
                        ];
                        PurchaseProducts::insert($data);

                    }
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

        $data['purchase']=Purchase::find($purchase->id);
        $data['purchase_products']=PurchaseProducts::with('product','optionvalue')->where('purchase_id',$purchase->id)->get();
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
        
        PurchaseProducts::where('purchase_id',$purchase_id)->delete();
        $quantity=$request->quantity;
        $subtotal=$request->subtotal;
            foreach ($quantity as $product_id => $option_values) {
                foreach ($option_values as $option_id => $option_values) {
                    foreach ($option_values as $op_value_id => $op_values) {
                        $sub_total=$subtotal[$product_id][$option_id][$op_value_id];
                        $selling_price_details=DB::table('product_variants')
                                               ->where('product_id',$product_id)
                                               ->where('option_id',$option_id)
                                               ->where('option_value_id',$op_value_id)
                                               ->first();
                        $data=[
                            'purchase_id'=> $purchase_id,
                            'product_id'=>$product_id,
                            'option_id'=>$option_id,
                            'option_value_id'=>$op_value_id,
                            'base_price'=> $selling_price_details->base_price,
                            'retail_price'=>$selling_price_details->retail_price,
                            'minimum_selling_price'=>$selling_price_details->minimum_selling_price ,
                            'quantity'=>$op_values,
                            'discount'=>0,
                            'product_tax'=>0,
                            'sub_total'=>$sub_total
                        ];
                        PurchaseProducts::insert($data);

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
    public function destroy(Purchase $purchase)
    {
        Purchase::where('id',$purchase->id)->delete();
        PurchaseProducts::where('purchase_id',$purchase->id)->delete();

        return Redirect::route('purchase.index')->with('success','Purchase order deleted successfully...!');

    }
    public function ProductSearch(Request $request)
    {

        $search_type=$request->product_search_type;
        if ($search_type=="options") {
            $product_id=$request->product_id;
            $data=$options=array();
            $options=$this->Options($product_id);
            $data['product_variant']=$this->Variants($product_id);
            $data['options'] = $options['options'];
            $data['option_count'] = $options['option_count'];


            dd($data);

            return view('admin.products.variations',$data);
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
    public function Options($product_id)
    {
    $variant = ProductVariant::where('product_id',$product_id)
                   ->where('is_deleted',0)->first();

    $optionName1=DB::table('product_option_values')->where('id',$variant->option_value_id)->value('option_value');

    $optionName2=DB::table('product_option_values')->where('id',$variant->option_value_id)->value('option_value');
    $optionName3=DB::table('product_option_values')->where('id',$variant->option_value_id3)->value('option_value');
    $optionName4=DB::table('product_option_values')->where('id',$variant->option_value_id4)->value('option_value');
    $optionName5=DB::table('product_option_values')->where('id',$variant->option_value_id5)->value('option_value');

    $options=array();
    if(($variant->option_id!=NULL)&&($variant->product_option_id2==NULL)&&($variant->product_option_id3==NULL)){
            $options[] = $optionName1;
            $option_count = 1;
        }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3==NULL)){
            $options[] = $optionName1;
            $options[] = $optionName2;
            $option_count = 2;
        }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)){
            $options[] = $optionName1;
            $options[] = $optionName2;
            $options[] = $optionName3;
            $option_count = 3;
        }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)&&($variant->product_option_id4!=NULL)){
            $options[] = $optionName1;
            $options[] = $optionName2;
            $options[] = $optionName3;
            $options[] = $optionName4;
            $option_count = 4;
        }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)&&($variant->product_option_id4!=NULL)&&($variant->product_option_id5!=NULL)){
            $options[] = $optionName1;
            $options[] = $optionName2;
            $options[] = $optionName3;
            $options[] = $optionName4;
            $options[] = $optionName5;
            $option_count = 5;
        }

        return ['options'=>$options,'option_count'=>$option_count];
    }
    public function Variants($id)
    {
        $productVariants = ProductVariant::where('product_id',$id)->where('is_deleted',0)->get();

        $variant = ProductVariant::where('product_id',$id)->where('is_deleted',0)->first();
  $optionName1=DB::table('product_option_values')->where('id',$variant->option_value_id)->value('option_value');

    $optionName2=DB::table('product_option_values')->where('id',$variant->option_value_id)->value('option_value');
    $optionName3=DB::table('product_option_values')->where('id',$variant->option_value_id3)->value('option_value');
    $optionName4=DB::table('product_option_values')->where('id',$variant->option_value_id4)->value('option_value');
    $optionName5=DB::table('product_option_values')->where('id',$variant->option_value_id5)->value('option_value');

        $product_variants = array();
        foreach ($productVariants as $key => $variants) {
            $product_variants[$key]['variant_id'] = $variants->id;
            if(($variant->option_id!=NULL)&&($variant->product_option_id2==NULL)&&($variant->product_option_id3==NULL)){
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $optionName1;
            }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3==NULL)){
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $optionName1;
                $product_variants[$key]['option_id2'] = $variants->product_option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->product_option_value_id2;
                $product_variants[$key]['option_value2'] = $optionName2;
            }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)){
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $optionName1;
                $product_variants[$key]['option_id2'] = $variants->product_option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->product_option_value_id2;
                $product_variants[$key]['option_value2'] = $optionName2;
                $product_variants[$key]['option_id3'] = $variants->product_option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->product_option_value_id3;
                $product_variants[$key]['option_value3'] = $optionName3;
            }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)&&($variant->product_option_id4!=NULL)){
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $optionName1;
                $product_variants[$key]['option_id2'] = $variants->product_option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->product_option_value_id2;
                $product_variants[$key]['option_value2'] = $optionName2;
                $product_variants[$key]['option_id3'] = $variants->product_option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->product_option_value_id3;
                $product_variants[$key]['option_value3'] = $optionName3;
                $product_variants[$key]['option_id4'] = $variants->product_option_id4;
                $product_variants[$key]['option_value_id4'] = $variants->product_option_value_id4;
                $product_variants[$key]['option_value4'] = $optionName4;
            }elseif(($variant->option_id!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)&&($variant->product_option_id4!=NULL)&&($variant->product_option_id5!=NULL)){
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $optionName1;
                $product_variants[$key]['option_id2'] = $variants->product_option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->product_option_value_id2;
                $product_variants[$key]['option_value2'] = $optionName2;
                $product_variants[$key]['option_id3'] = $variants->product_option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->product_option_value_id3;
                $product_variants[$key]['option_value3'] = $optionName3;
                $product_variants[$key]['option_id4'] = $variants->product_option_id4;
                $product_variants[$key]['option_value_id4'] = $variants->product_option_value_id4;
                $product_variants[$key]['option_value4'] = $optionName4;
                $product_variants[$key]['option_id5'] = $variants->product_option_id5;
                $product_variants[$key]['option_value_id5'] = $variants->product_option_value_id5;
                $product_variants[$key]['option_value5'] = $optionName5;
            }
            $product_variants[$key]['base_price'] = $variants->base_price;
            $product_variants[$key]['retail_price'] = $variants->retail_price;
            $product_variants[$key]['minimum_selling_price'] = $variants->minimum_selling_price;
            $product_variants[$key]['display_order'] = $variants->display_order;
            $product_variants[$key]['stock_quantity'] = $variants->stock_quantity;
            $product_variants[$key]['display_variant'] = $variants->display_variant;
            $product_variants[$key]['vendor_id'] = $variants->vendor_id;

            $vendor_name=Vendor::find($purchase->vendor_id)->name;
            $product_variants[$key]['vendor_name'] = $variants->vendorName->name;
        }


        return $product_variants;
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
