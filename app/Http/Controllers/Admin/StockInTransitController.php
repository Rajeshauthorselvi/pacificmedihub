<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\OptionValue;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Session;
use Redirect;
use DB;

class StockInTransitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();

        $purchases=Purchase::orderBy('id','DESC')->get();
        $data=array();
        $orders=array();
        foreach ($purchases as $key => $purchase) {
            $vendor_name=Vendor::find($purchase->vendor_id)->name;
            $product_details=PurchaseProducts::select(DB::raw('sum(quantity) as quantity'),DB::raw('sum(qty_received) as qty_received'))
                ->where('purchase_id',$purchase->id)
                ->first();

        $order_status=OrderStatus::where('status',1)
                              ->where('id',$purchase->purchase_status)
                              ->value('status_name');

            $orders[]=[
                'purchase_date'=>$purchase->purchase_date,
                'purchase_id'=>$purchase->id,
                'vendor'   =>$vendor_name,
                'po_number'=>$purchase->purchase_order_number,
                'quantity' => $product_details->quantity,
                'qty_received' => $product_details->qty_received,
                'status' =>$order_status
            ];
        }

        $data['orders']=$orders;
        return view('admin.stock.stock-in-transit.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $data=array();
         $data['purchase']=Purchase::find($id);
         // $data['purchase_products']=PurchaseProducts::with('product','optionvalue')->where('purchase_id',$id)->get();

         $product_purchase=PurchaseProducts::where('purchase_id',$id)->get();

         $pro_datas=array();

         foreach ($product_purchase as $key => $products) {
            $product_name=Product::where('id',$products->product_id)->value('name');

             $pro_datas[]=[
                'product_purchase_id'  => $products->id,
                'product_id'  => $products->product_id,
                'product_name'  => $product_name,
                'option_value_id1'  => $this->OptionValues($products->option_value_id),
                'option_value_id2'  => $this->OptionValues($products->option_value_id2),
                'option_value_id3'  => $this->OptionValues($products->option_value_id3),
                'option_value_id4'  => $this->OptionValues($products->option_value_id4),
                'option_value_id5'  => $this->OptionValues($products->option_value_id5),
                'qty_received'  => $products->qty_received,
                'issue_quantity'  => $products->issue_quantity,
                'reason'  => $products->reason,
                'quantity'  => $products->quantity,
             ];

             $options=$this->Options($products->product_id);
         }

         $data['options']=$options;

         $data['product_datas']=$pro_datas;

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

        return view('admin.stock.stock-in-transit.edit',$data);
    }
    public function OptionValues($value_id='')
    {
        $option_value=OptionValue::where('id',$value_id)->value('option_value');

        return $option_value;
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

        return $options;
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
            'purchase_status'   => 'required'
        ]);

        $quantity_received=$request->qty_received;

        foreach ($quantity_received as $variant_id => $quantity) {
        $issued_quantity=$request->issue_quantity;
        $reason=$request->reason;

            PurchaseProducts::where('id',$variant_id)
            ->update([
                'qty_received'  => $quantity,
                'issue_quantity' => $issued_quantity[$variant_id],
                'reason'         => $reason[$variant_id]
            ]);
        }
           
            Purchase::where('id',$id)->update(['stock_notes'=>$request->stock_notes,'purchase_status'=>$request->purchase_status]);

            return Redirect::route('stock-in-transit.index')->with('success','Stock-In-Transit modified successfully...!');  
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
}
