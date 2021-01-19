<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\OrderStatus;
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
            $product_names=DB::table('products as p')
                           ->select('p.id as product_id','p.name as product_name','pov.option_value','pv.*')
                           ->join('product_variants as pv','p.id','pv.product_id')
                           ->join('product_option_values as pov','pov.id','pv.option_value_id')
                           ->where("p.id",$product_id)
                           ->get();

            $names=array();
            foreach ($product_names as $key => $name) {
                $names[]=[
                    'value' => $name->product_id,
                    'label' => $name->product_name,
                    'base_price' => $name->base_price,
                    'retail_price'=> $name->retail_price,
                    'minimum_selling_price'=> $name->minimum_selling_price,
                    'option_value'=> $name->option_value,
                    'option_id' =>$name->option_id,
                    'option_value_id'=>$name->option_value_id
                ];
            }
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
}
