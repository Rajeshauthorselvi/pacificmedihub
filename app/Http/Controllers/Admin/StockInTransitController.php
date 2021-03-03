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
use App\Models\PurchaseStockHistory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\PurchaseReturn;
use App\Models\PurchaseProductReturn;
use Session;
use Redirect;
use DB;
use Auth;

class StockInTransitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('stock_transist_vendor','read')) {
                abort(404);
            }
        }
        $data=array();

        $purchases=Purchase::orderBy('id','DESC')->get();
        $data=array();
        $orders=array();
        foreach ($purchases as $key => $purchase) {
            $vendor_name=Vendor::find($purchase->vendor_id)->name;
            $product_details=PurchaseProducts::select(DB::raw('sum(quantity) as quantity'))
                ->where('purchase_id',$purchase->id)
                ->first();

            $total_qty_received=PurchaseStockHistory::where('purchase_id',$purchase->id)->sum('qty_received');
        $order_status=OrderStatus::where('status',1)
                              ->where('id',$purchase->purchase_status)
                              ->first();
          $total_return_amount=PurchaseStockHistory::where('purchase_id',$purchase->id)->where('goods_type',1)->sum('damage_quantity');
            $orders[]=[
                'purchase_date'=>$purchase->purchase_date,
                'purchase_id'=>$purchase->id,
                'vendor'   =>$vendor_name,
                'po_number'=>$purchase->purchase_order_number,
                'quantity' => $product_details->quantity,
                'qty_received' => $total_qty_received-$total_return_amount,
                'status' =>$order_status->status_name,
                'color_code'     => $order_status->color_codes
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
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('stock_transist_vendor','update')) {
                abort(404);
            }
        }
        $data=array();
        $data['purchase']=Purchase::find($id);

        $products=PurchaseProducts::where('purchase_id',$id)->groupBy('product_id')->get();

            $product_data=$product_variant=array();
            foreach ($products as $key => $product) {
                $product_name=Product::where('id',$product->product_id)->value('name');
                $options=$this->Options($product->product_id);

                $all_variants=PurchaseProducts::where('purchase_id',$id)
                              ->where('product_id',$product->product_id)
                              ->pluck('product_variation_id')
                              ->toArray();

                $product_variant=$this->Variants($product->product_id,$all_variants);
                $product_data[$product->product_id]=[
                    'row_id'         => $product->id,
                    'purchase_id'    => $id,
                    'product_id'=> $product->product_id,
                    'product_name'  => $product_name,
                    'options'       => $options['options'],
                    'option_count'  => $options['option_count'],
                    'product_variant'  => $product_variant
                ];
            }
            $data['purchase_products']=$product_data;


        $data['product_name']=$product_name;

        $order_status=OrderStatus::where('status',1)
                      ->whereIn('id',[1,2,4])
                      ->pluck('status_name','id')
                      ->toArray();

        $payment_method=PaymentMethod::where('status',1)
                              ->pluck('payment_method','id')
                              ->toArray();
        $vendors=Vendor::where('is_deleted',0)
                         ->where('status',1)
                         ->pluck('name','id')
                         ->toArray();
        $data['received_quantity']=$this->TotalReceivedQuantity($id);

        $data['existing_history']=$this->StockHistoryDetails($id);
        $data['vendors']=[''=>'Please Select']+$vendors;
        $data['order_status']=[''=>'Please Select']+$order_status;
        $data['payment_method']=[''=>'Please Select']+$payment_method;
        return view('admin.stock.stock-in-transit.edit',$data);
    }

    public function TotalReceivedQuantity($purchase_id)
    {
          $total_received_history=PurchaseStockHistory::where('purchase_id',$purchase_id)
                                         ->select(DB::raw('sum(qty_received) as total_quantity'),'purchase_id','purchase_product_id')
                                         ->groupBy('purchase_product_id')
                                         ->get();
          $data=array();
          foreach ($total_received_history as $key => $history) {
              $data[$history->purchase_product_id]=$history->total_quantity;
          }

        return $data;
    }
    public function update(Request $request, $id)
    {
      // dd($request->all());
        $this->validate(request(),[
            'purchase_status'   => 'required'
        ]);
       $quantity_received=$request->qty_received;

       $variant=$request->variant;
       $row_ids=$variant['row_id'];
       $qty_received=$variant['qty_received'];
       $damaged_qty=$variant['damaged_qty'];

       $missed_quantity=$variant['missed_qty'];
       $stock_quantity=$variant['stock_quantity'];
       $product_id=$variant['product_id'];
       $product_id=$variant['product_id'];
       if(isset($variant['goods_type'])){
         $goods_type=$variant['goods_type'];
       }
       
       $status=1;
       if ($request->purchase_status!=1) {
         foreach ($row_ids as $key => $row_id) {

            $purchase_data=DB::table('purchase_products')->where('id',$row_id)->first();
            $variant_data=DB::table('product_variant_vendors')
                          ->where('product_variant_id',$purchase_data->product_variation_id)
                          ->first();

              /*Add Stock History*/
              if ($qty_received[$key]!=0) {
                $data=[
                  'purchase_id'           => $id,
                  // 'product_id'            => $product_id[$key],
                  'purchase_product_id'   => $row_id,
                  'qty_received'          => $qty_received[$key],
                  'damage_quantity'       => $damaged_qty[$key],
                  'missed_quantity'       => $missed_quantity[$key],
                  'stock_quantity'        => $stock_quantity[$key],
                  'created_at'            => date('Y-m-d H:i:s'),
                  'goods_type'            => isset($goods_type[$key])?$goods_type[$key]:''
                ];
                $history_id=PurchaseStockHistory::insertGetId($data);
              }
              /*Add Stock History*/

              /*Update Stock Quantity*/
              if ($request->purchase_status==2 || $request->purchase_status==4) {
                $total_quantity=$variant_data->stock_quantity+$stock_quantity[$key];
                  DB::table('product_variant_vendors')
                  ->where('product_variant_id',$purchase_data->product_variation_id)
                  ->update(['stock_quantity'=>$total_quantity]);
              }
              /*Update Stock Quantity*/

          }
          $total_quantity=PurchaseProducts::where('purchase_id',$id)->sum('quantity');
          $return_quantity=PurchaseStockHistory::where('purchase_id',$id)
                           ->where('goods_type',1)
                           ->sum('damage_quantity');
          $paid_quantity=PurchaseStockHistory::where('purchase_id',$id)->sum('stock_quantity');

          if (($total_quantity-$return_quantity)==$paid_quantity) {
              $status=2;
          }
          elseif ($total_quantity!=$paid_quantity) {
            $status=4;
          }
       }
        Purchase::where('id',$id)->update(['stock_notes'=>$request->stock_notes,'purchase_status'=>$status]);

        /*Add record to return table*/
          $check_quantity_exists = array_filter($damaged_qty, function($value){
                  return $value > 0;
              });
        if (count($check_quantity_exists)>0) {
          $purchase_details=Purchase::find($id);
          // $payment_status=[''=>'Please Select',1=>'Paid',2=>'Partly Paid',3=>'Not Paid'];
          $data_return=[
              // 'purchase_history_id'     => $history_id,
              'purchase_or_order_id'    => $id,
              'customer_or_vendor_id'   => $purchase_details->vendor_id,
              'order_type'              => 1,
              'payment_status'          => 3,
              'return_status'           => 5,
              'staff_notes'             => "",
              'created_at'              => date('Y-m-d H:i:s')
          ];
          $return_id=PurchaseReturn::updateOrCreate(['purchase_or_order_id'=> $id],$data_return);
          $variant_ids=$variant['variant_id'];
          foreach ($variant_ids as $key => $row_id) {
            // var_dump($damaged_qty[$key]);
            if (($damaged_qty[$key]>0) && ($goods_type[$key]==1)) {

              $existing_damage=PurchaseProductReturn::where(['product_id'=> $product_id[$key],'purchase_variation_id'=>$row_id,'purchase_return_id'=>$return_id->id])
              ->sum('damage_quantity');

              $damage_total=$existing_damage+$damaged_qty[$key];

              $stock_price=PurchaseProducts::where('id',$id)->value('base_price');
              $data_return_products=[
                'product_id'              => $product_id[$key],
                'purchase_variation_id'   => $row_id,
                'damage_quantity'         => $damage_total,
                'return_quantity'         => $damage_total,
                'return_sub_total'        => $damage_total*$stock_price,
                'purchase_return_id'      => $return_id->id
              ];

               $return_product_id=PurchaseProductReturn::updateOrCreate(
                ['product_id'=> $product_id[$key],'purchase_variation_id'=>$row_id,'purchase_return_id'=>$return_id->id],
                $data_return_products
              );

            }

          }
        }

        /*Add record to return table*/

        return Redirect::route('stock-in-transit.index')
               ->with('success','Stock-In-Transit modified successfully...!');  
        }

    public function StockHistoryDetails($purchase_id)
    {
        $purchase_history=PurchaseStockHistory::where('purchase_id',$purchase_id)->get();
        $data=array();
        foreach ($purchase_history as $key => $history) {
            $data[$history->product_variation_id]=[
              'qty_received'  => $history->qty_received,
              'damage_quantity'  => $history->damage_quantity,
              'damage_quantity'  => $history->damage_quantity,
              'missed_quantity'  => $history->missed_quantity,
              'created_at'  => date('d-m-Y H:i:s'),
            ];
        }
        return $data;
    }
    public function OptionValues($value_id='')
    {
        $option_value=OptionValue::where('id',$value_id)->value('option_value');

        return $option_value;
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
  public function Variants($product_id,$variation_id)
    {

        $variant = ProductVariant::where('product_id',$product_id)->where('disabled',0)->where('is_deleted',0)->first();

        if (isset($variation_id)) {

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

    public function ListPurchaseStockHistory(Request $request)
    {

      $data=$history=array();

      $purchase_variant_id=$request->get('purchase_variant_id');
      $purchase_id=$request->get('purchase_id');
      $product_id=$request->get('product_id');
      $product_variant_id=$request->get('product_variant');

      $purchase_details=PurchaseStockHistory::where('purchase_id',$purchase_id)
                        ->where('purchase_product_id',$purchase_variant_id)
                        ->get();

      $product_name=DB::table('purchase_stock_history as psh')
                        ->leftjoin('purchase_products as pp','pp.id','psh.purchase_product_id')
                        ->leftjoin('products as p','p.id','pp.product_id')
                        ->where('psh.purchase_product_id',$purchase_variant_id)
                        ->where('psh.purchase_id',$purchase_id)
                        ->value('name');


      $options=$this->Options($product_id);

      $data['option_count']=$options['option_count'];
      $data['options']=$options['options'];
      $all_variants=PurchaseProducts::where('id',$purchase_variant_id)->pluck('product_variation_id')->toArray();
      $data['product_variants']=$this->Variants($product_id,$all_variants);

      $data['product_name']    = $product_name;
      foreach ($purchase_details as $key => $purchase_detail) {
          $history[]=[
              'qty_received'    => $purchase_detail->qty_received,
              'damage_quantity' => $purchase_detail->damage_quantity,
              'missed_quantity' => $purchase_detail->missed_quantity,
              'stock_quantity'  => $purchase_detail->stock_quantity,
              'created_at'      => date('d-m-Y H:i A',strtotime($purchase_detail->created_at)),
              'goods_type'      => $purchase_detail->goods_type
          ];
      }
      $data['histories']=$history;

      $data['purchase_product_details']=PurchaseProducts::where('id',$purchase_variant_id)->first();
      $data['purchase_datas']=Purchase::where('id',$purchase_id)->value('created_at');

      return view('admin.stock.stock-in-transit.stock_history',$data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
