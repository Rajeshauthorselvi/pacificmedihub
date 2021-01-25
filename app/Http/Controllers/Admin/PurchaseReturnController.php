<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProductReturn;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\OptionValue;
use App\Models\ProductVariant;
use Redirect;
use DB;
class PurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_return=array();
        $returns=PurchaseReturn::all();
        foreach ($returns as $key => $return) {
            $purchase=Purchase::where('id',$return->purchase_or_order_id)
                      ->value('purchase_order_number'); 

            $total_quantity=PurchaseProductReturn::where('purchase_return_id',$return->id)->sum('return_quantity');

            $sub_total=PurchaseProductReturn::where('purchase_return_id',$return->id)->sum('return_sub_total');

            $vendor=Vendor::where('id',$return->customer_or_vendor_id)
                      ->value('name');
            $data_return[]=[
                'id'    => $return->id,
                'date'  => date('d-m-Y',strtotime($return->created_at)),
                'po_number' =>$purchase,
                'vendor' =>$vendor,
                'order_type' =>$return->order_type,
                'total_quantity' =>$total_quantity,
                'sub_total' =>$sub_total,
                'payment_status' =>1,
                'return_status' =>1,
            ];
        }
        $data['returns']=$data_return;
        return view('admin.stock.return.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=array();
        return view('admin.stock.return.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $purchase=Purchase::where('id',$request->purchase_id)->first();

        $quantites=$request->quantity;
        $sub_total=$request->sub_total;
          $data= [ 
                'purchase_or_order_id'  => (int)$request->purchase_id,
                'customer_or_vendor_id' =>  $purchase->vendor_id,
                'order_type'            => 1,
                'payment_status'        => 1,
                'return_status'         => 1,
                'return_notes'           => $request->return_notes,
                'staff_notes'           =>$request->staff_notes,
                'created_at'            =>date('Y-m-d H:i:s')
            ];

        $return_id=PurchaseReturn::insertGetId($data);
        foreach ($quantites as $key => $quantity) {
            $data=[
                'purchase_return_id'    =>$return_id,
                'product_id'            =>$request->product_id,
                'purchase_variation_id' =>$key,
                'return_quantity'       =>$quantity,
                'return_sub_total'      => $sub_total[$key]
            ];
        PurchaseProductReturn::insert($data);
        }

        return Redirect::route('return.index')->with('success','Purchase order created successfully...!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseReturn $purchaseReturn,$slug)
    {

        $purchase_detail=Purchase::where('purchase_order_number',$slug)
                               ->first();

        if (!$purchase_detail) {
            return ['status'=>false,'message'=>'No order found'];
        }

            $data['vendor_name']=Vendor::where('id',$purchase_detail->vendor_id)
                                 ->value('name');
            $data['purchase']=$purchase_detail;
            $data['purchase_date']=$purchase_detail->purchase_date;
            $id=$purchase_detail->id;
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
                'base_price'  => $products->base_price,
                'retail_price'  => $products->retail_price,
                'minimum_selling_price'  => $products->minimum_selling_price,
                'quantity'  => $products->quantity,
             ];

             $options=$this->Options($products->product_id);
         }

         $data['options']=$options;

         $data['product_datas']=$pro_datas;
         $data['purchase_id']=$id;
        $view=view('admin.stock.return.products',$data)->render();

        return ['status'=>true,'view'=>$view];

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
    public function OptionValues($value_id='')
    {
        $option_value=OptionValue::where('id',$value_id)->value('option_value');

        return $option_value;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseReturn $purchaseReturn,$id)
    {
        $data=array();
        $data['purchase_detail']=$purchase_detail=PurchaseReturn::where('id',$id)->first();
        $data['return_quantity']=PurchaseProductReturn::where('purchase_return_id',$id)
                         ->pluck('return_quantity','purchase_variation_id')
                         ->toArray();
        $data['return_subtotal']=PurchaseProductReturn::where('purchase_return_id',$id)
                    ->pluck('return_sub_total','purchase_variation_id')
                    ->toArray();
 $data['vendor_name']=Vendor::where('id',$purchase_detail->customer_or_vendor_id)
                                 ->value('name');
                                 
            $data['purchase']=$purchase_detail;
            $data['purchase_date']=$purchase_detail->purchase_date;
            $id=$purchase_detail->id;

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
                'base_price'  => $products->base_price,
                'retail_price'  => $products->retail_price,
                'minimum_selling_price'  => $products->minimum_selling_price,
                'quantity'  => $products->quantity,
             ];
             $options=$this->Options($products->product_id);
         }

         $data['options']=$options;

         $data['product_datas']=$pro_datas;
         $data['purchase_id']=$id;
        return view('admin.stock.return.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseReturn $purchaseReturn,$return_id)
    {

        $quantites=$request->quantity;
        $sub_total=$request->sub_total;
        PurchaseReturn::where('purchase_or_order_id',$request->purchase_id)
            ->update([ 
                'return_notes'           => $request->return_notes,
                'staff_notes'           =>$request->staff_notes
            ]);

        foreach ($quantites as $key => $quantity) {
            $data=[
                'purchase_return_id'    =>$return_id,
                'product_id'            =>$request->product_id,
                'purchase_variation_id' =>$key,
                'return_quantity'       =>$quantity,
                'return_sub_total'      => $sub_total[$key]
            ];
        PurchaseProductReturn::where([
                'purchase_return_id'    =>$return_id,
                'product_id'            =>$request->product_id,
                'purchase_variation_id' =>$key
            ])
            ->update([
                'return_quantity'       =>$quantity,
                'return_sub_total'      => $sub_total[$key]
            ]);
        }
        return Redirect::route('return.index')->with('success','Purchase return modified successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseReturn  $purchaseReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseReturn $purchaseReturn)
    {
        //
    }
}
