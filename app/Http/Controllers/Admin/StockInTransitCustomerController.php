<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerOrderReturn;
use App\Models\CustomerOrderReturnProducts;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Options;
use App\Models\OptionValues;
use Redirect;
use DB;
use Auth;

class StockInTransitCustomerController extends Controller
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
      $data = $returns = array();
      $order_returns = CustomerOrderReturn::orderBy('id','desc')->get();
      foreach ($order_returns as $value) {
        $order = Orders::find($value->order_id);
        $order_products_qty = OrderProducts::where('order_id',$order->id)->sum('quantity');
        $return_products_recived_qty = CustomerOrderReturnProducts::where('customer_order_return_id',$value->id)->where('order_id',$value->order_id)->sum('qty_received');
        $return_products_return_qty = CustomerOrderReturnProducts::where('customer_order_return_id',$value->id)->where('order_id',$value->order_id)->sum('total_return_quantity');
        $returns[]=[
          'return_id'       => $value->id,
          'return_date'     => date('d-m-Y',strtotime($value->created_at)),
          'customer'        => $value->customer->name,
          'order_number'    => $order->order_no,
          'quantity'        => $order_products_qty,
          'return_quantity' => $return_products_return_qty,
          'status_id'       => $value->statusName->id,
          'status'          => $value->statusName->status_name,
          'color_code'      => $value->statusName->color_codes
        ];
      }
      $data['returns'] = $returns;
      return view('admin/stock/stock-in-transit-customer/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $order = Orders::find($request->order_id);
      $return = CustomerOrderReturn::where('id',$request->return_id)->first();
      $return->order_return_status = $request->status;
      $return->save();
      if($request->status==2)
      {
        $total = 0;
        $subTotal = 0;
        foreach ($request->variant['product_id'] as $key => $variant) {
          $variant  = ProductVariantVendor::where('product_variant_id',$request->variant['variant_id'][$key])
                          ->where('product_id',$request->variant['product_id'][$key])->first();
          $quantity = $variant->stock_quantity + $request->variant['return_qty'][$key];
          $variant->stock_quantity  =  $quantity;
          $variant->save();

          $order_product = OrderProducts::where('product_variation_id',$request->variant['variant_id'][$key])
                              ->where('product_id',$request->variant['product_id'][$key])->first();
          $order_qty = $order_product->quantity - $request->variant['total_return_quantity'][$key];
          $sub_total = $request->variant['total_return_quantity'][$key] * $order_product->final_price;
          $sub_total_amt = $order_product->sub_total - $sub_total;
          $order_product->quantity = $order_qty;
          $order_product->sub_total = $sub_total_amt;
          $subTotal += $sub_total;
          $total += $sub_total_amt;
          $order_product->save();
        }
        $order->total_amount = $total;
        $order->sgd_total_amount = $order->sgd_total_amount - $subTotal;
        $order->exchange_total_amount = $order->exchange_total_amount - $subTotal;
        $order->save();
      }
      return Redirect::route('stock-in-transit-customer.index')->with('success','Stock Updated successfully...!');  
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
        $data = array();
        $data['return'] = $return = CustomerOrderReturn::find($id);
        $data['return_products']  = $return_products = CustomerOrderReturnProducts::where('customer_order_return_id',$return->id)->get();
        $data['order_status']     = OrderStatus::where('status',1)->whereIn('id',[2,7,24])->orderBy('status_name','asc')
                                               ->pluck('status_name','id')->toArray(); 

        $product_data = $product_variant = array();

        foreach ($return_products as $key => $product) {
            $product_name = Product::where('id',$product->order_product_id)->value('name');
            $options      = $this->Options($product->order_product_id);
            $all_variants = OrderProducts::where('order_id',$return->order_id)
                                         ->where('product_id',$product->order_product_id)
                                         ->pluck('product_variation_id')
                                         ->toArray();
            $product_variant = $this->Variants($product->order_product_id,$all_variants);

            $product_data[$product->order_product_id]=[
                'row_id'          => $product->id,
                'return_id'       => $id,
                'product_id'      => $product->order_product_id,
                'product_name'    => $product_name,
                'options'         => $options['options'],
                'option_count'    => $options['option_count'],
                'product_variant' => $product_variant
            ];
        }
        $data['return_products'] = $product_data;
        $data['product_name']    = $product_name;
        //dd($data);
        return view('admin/stock/stock-in-transit-customer/update',$data);
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
        //
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
}
