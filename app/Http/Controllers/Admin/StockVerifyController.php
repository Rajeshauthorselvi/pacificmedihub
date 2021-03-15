<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Product;
use App\Models\ProductVariantVendor;
use App\Models\OrderProducts;
use App\Models\ProductVariant;
use App\Models\Employee;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\PaymentTerm;
use App\Models\Tax;
use App\Models\Prefix;
use App\Models\Purchase;
use App\User;
use App\Events\StockNotificationEvent;
use Mail;
use DB;
use Redirect;
class StockVerifyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order_id=$request->order_id;
        $data['order']=$order_details=Orders::with('orderProducts')->where('orders.id',$order_id)->first();
        $low_quantity_data=array();
        $products = OrderProducts::where('order_id',$order_id)->get();
        $product_data=array();
        foreach ($products as $key => $product) {
            $check_product_quantity=ProductVariantVendor::where('product_id',$product->product_id)->where('product_variant_id',$product->product_variation_id)->sum('stock_quantity');

            if ($product->quantity > $check_product_quantity) {
                $product_name    = Product::where('id',$product->product_id)->value('name');
                $options         = $this->Options($product->product_id);
                $all_variants=$this->VariantIds($order_id);
                $product_variant = $this->Variants($product->product_id,$all_variants);
                $product_data[$product->product_id] = [
                    'order_id'        => $order_id,
                    'product_id'      => $product->product_id,
                    'product_name'    => $product_name,
                    'options'         => $options['options'],
                    'option_count'    => $options['option_count'],
                    'product_variant' => $product_variant,
                    'order_quantity'        => $product->quantity,
                    'total_avail_quantity'  => $check_product_quantity
                ];
            }
        }
      if ($order_details->created_user_type==2) {
        $creater_name=Employee::where('id',$order_details->customer_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$order_details->customer_id)->first();
        $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
      }

      $data['creater_name']=$creater_name;
      $data['product_data']=$product_data;
        Mail::send('admin.orders.assign_shippment_delivery.notification_email', $data, function ($m) use($order_details) {
         $m->from('dhinesh@authorselvi.com');
           $m->to('dhinesh@authorselvi.com', 'Notification')->subject($order_details->order_no.'-Need quantity');
       });


        return Redirect::back()->with('success','Notification sent successfully...!');
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
            $product_variants[$key]['sku']=$variants->sku;

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
    public function show($order_id)
    {

        $low_quantity_data=array();
         $data['order']=$order_details=Orders::with('orderProducts')->where('orders.id',$order_id)->first();
        $products = OrderProducts::where('order_id',$order_id)->get();
        $product_data=array();
        foreach ($products as $key => $product) {
            $check_product_quantity=ProductVariantVendor::where('product_id',$product->product_id)->where('product_variant_id',$product->product_variation_id)->sum('stock_quantity');

            if ($product->quantity > $check_product_quantity) {
         
            //$low_stock=Orders::LowQuantityInStockVerifyPage($product->product_id,$product->product_variation_id);

                $product_name    = Product::where('id',$product->product_id)->value('name');
                $all_variants    = OrderProducts::where('order_id',$order_id)->where('product_id',$product->product_id)->pluck('product_variation_id')->toArray();
                $product_data[] = [
                    'order_id'        => $order_id,
                    'product_id'      => $product->product_id,
                    'product_name'    => $product_name,
                    'order_quantity'        => $product->quantity,
                    'total_avail_quantity'  => $check_product_quantity
                ];
            }
        }

      if ($order_details->created_user_type==2) {
        $creater_name=Employee::where('id',$order_details->customer_id)->first();
        $creater_name=$creater_name->emp_name;
      }
      else{
        $creater_name=User::where('id',$order_details->customer_id)->first();
        $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
      }

      $data['creater_name']=$creater_name;
      $data['product_data']=$product_data;

      return view('admin.stock.stock_verify.index',$data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$order_id)
    {
      $data=array();

          $product_id=$request->product_id;
          $variant_id=$request->product_variant_id;
          $vendor_id=$request->vendor_id;

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

            $order_details=Orders::where('id',$order_id)->first();
            $order_products=OrderProducts::where('order_id',$order_id)->get();
            $product_data=$all_product_ids=array();
            foreach ($order_products as $key => $product) {
            $check_product_quantity=ProductVariantVendor::where('product_id',$product->product_id)->where('product_variant_id',$product->product_variation_id)->sum('stock_quantity');

            if ($product->quantity > $check_product_quantity) {
         
                $all_variants=$this->VariantIds($order_id);

                $product_name    = Product::where('id',$product->product_id)->value('name');
                $product_variant = $this->Variants($product->product_id,$all_variants);
                $options         = $this->Options($product->product_id);
                $product_data[] = [
                    'order_id'        => $order_id,
                    'product_id'      => $product->product_id,
                    'product_name'    => $product_name,
                    'order_quantity'        => $product->quantity,
                    'total_avail_quantity'  => $check_product_quantity,
                    'options'               => $options['options'],
                    'option_count'          => $options['option_count'],
                    'product_variant'       => $product_variant
                ];
                $all_product_ids[]=$product->product_id;
            }
        }
        $data['purchase_products'] = $product_data;
        $all_vendors=DB::table('product_variant_vendors as pvv')
                   ->leftjoin('vendors as v','v.id','pvv.vendor_id')
                   ->where('status',1)
                   ->whereIn('product_id',$all_product_ids)
                   ->pluck('name','v.id')->toArray();
        $data['vendors']        = [''=>'Please Select']+$all_vendors;

        return view('admin.stock.stock_verify.create',$data);
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
    public function VariantIds($order_id='')
    {
        $product_variant=DB::select("SELECT op.product_variation_id FROM order_products as op where `quantity` > (SELECT stock_quantity from product_variant_vendors as pvv WHERE pvv.product_id=op.product_id and pvv.product_variant_id=op.product_variation_id) AND op.order_id='".$order_id."'");

            $all_variants=array();
            foreach ($product_variant as $key => $row) 
                $all_variants[$key] = $row->product_variation_id;

        return $all_variants;
    }
}
