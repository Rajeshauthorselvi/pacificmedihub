<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Employee;
use App\Models\OrderStatus;
use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\PaymentTerm;
use App\Models\UserAddress;
use App\Models\UserCompanyDetails;
use App\User;
use Redirect;
use Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
class CompletedOrders extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('completed_orders','read')) {
                abort(404);
            }
        }
        $data['completed_orders']=Orders::with('customer','deliveryPerson','statusName','deliveryStatus')
                              ->where('order_status',13)
                              ->orderBy('orders.id','desc')
                              ->get();

        return view('admin.orders.completed_orders.index',$data);
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
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('completed_orders','read')) {
                abort(404);
            }
        }
        $data['order']          = Orders::with('customer','salesrep','statusName','deliveryStatus')->where('orders.id',$order_id)
                                        ->first();

        $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',7)->pluck('first_name','id')->toArray();
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
        $data['order_status']   = OrderStatus::where('status',1)
                                  ->whereIn('id',[3,13,11])
                                  ->pluck('status_name','id')->toArray();
        $data['delivery_persons']=[''=>'Please Select']+Employee::where('emp_department',3)
                                  ->where('is_deleted',0)->where('status',1)
                                  ->pluck('emp_name','id')->toArray();

        $products = OrderProducts::where('order_id',$order_id)->groupBy('product_id')->get();

        $product_data = $product_variant=array();

        foreach ($products as $key => $product) {
            $product_name    = Product::where('id',$product->product_id)->value('name');
            $options         = $this->Options($product->product_id);
            $all_variants    = OrderProducts::where('order_id',$order_id)->where('product_id',$product->product_id)
                                    ->pluck('product_variation_id')
                                    ->toArray();
            $product_variant = $this->Variants($product->product_id,$all_variants);

            $product_data[$product->product_id] = [
                'order_id'        => $order_id,
                'product_id'      => $product->product_id,
                'product_name'    => $product_name,
                'options'         => $options['options'],
                'option_count'    => $options['option_count'],
                'product_variant' => $product_variant
            ];
        }
        $data['product_datas']=$product_data;
        $data['delivery_status']=[''=>'Please Select']+OrderStatus::where('status',1)
                                  ->whereIn('id',[14,15,16,17])
                                  ->pluck('status_name','id')->toArray();
        return view('admin.orders.completed_orders.view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($order_id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('completed_orders','update')) {
                abort(404);
            }
        }
        $data['order']          = Orders::with('customer','salesrep','statusName')->where('orders.id',$order_id)
                                        ->first();
        $data['customers']      = [''=>'Please Select']+User::where('is_deleted',0)->where('status',1)
                                        ->where('role_id',7)->pluck('first_name','id')->toArray();
        $data['sales_rep']      = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
        $data['order_status']   = OrderStatus::where('status',1)
                                  ->whereIn('id',[3,13,11])
                                  ->pluck('status_name','id')->toArray();
        $data['delivery_persons']=[''=>'Please Select']+Employee::where('emp_department',3)
                                  ->where('is_deleted',0)->where('status',1)
                                  ->pluck('emp_name','id')->toArray();

        $products = OrderProducts::where('order_id',$order_id)->groupBy('product_id')->get();

        $product_data = $product_variant=array();

        foreach ($products as $key => $product) {
            $product_name    = Product::where('id',$product->product_id)->value('name');
            $options         = $this->Options($product->product_id);
            $all_variants    = OrderProducts::where('order_id',$order_id)->where('product_id',$product->product_id)
                                    ->pluck('product_variation_id')
                                    ->toArray();
            $product_variant = $this->Variants($product->product_id,$all_variants);

            $product_data[$product->product_id] = [
                'order_id'        => $order_id,
                'product_id'      => $product->product_id,
                'product_name'    => $product_name,
                'options'         => $options['options'],
                'option_count'    => $options['option_count'],
                'product_variant' => $product_variant
            ];
        }
        $data['product_datas']=$product_data;
        $data['delivery_status']=[''=>'Please Select']+OrderStatus::where('status',1)
                                  ->whereIn('id',[14,15,16,17])
                                  ->pluck('status_name','id')->toArray();
        return view('admin.orders.completed_orders.edit',$data);
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

        $order=Orders::find($id);
        $order->delivery_person_id=$request->delivery_person_id;
        $order->approximate_delivery_date=date('Y-m-d',strtotime($request->delivery_date));
        $order->logistic_instruction=$request->notes;
        $order->delivery_status=$request->delivery_status;
        if ($request->delivery_status==16) {
            $order->delivered_at=date('Y-m-d H:i:s');
        }
        $order->save();

        return Redirect::route('completed-orders.index');
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

    public function COPrint($order_id)
    {

    $print_data=$this->PdfAndPrint($order_id);
    
    return view('admin.orders.completed_orders.print',$print_data['data']);

    }

    public function COPPdf($order_id)
    {
      $print_data=$this->PdfAndPrint($order_id);
      $data=$print_data['data'];
      $order_details=$data['order'];

      $layout = View::make('admin.orders.completed_orders.pdf',$print_data['data']);
      $pdf = App::make('dompdf.wrapper');
      $pdf->loadHTML($layout->render());
      return $pdf->download('COP-'.$order_details->order_no.'.pdf');
    }

    public function PdfAndPrint($order_id)
    {
        $data=array();
        $data['order']=$order = Orders::find($order_id);
        $data['admin_address']  = UserCompanyDetails::where('customer_id',1)->first();
        $data['customer_address']  = UserAddress::where('id',$order->address_id)->first();

          if ($order->created_user_type==2) {
            $creater_name=Employee::where('id',$order->user_id)->first();
            $creater_name=$creater_name->emp_name;
          }
          else{
            $creater_name=User::where('id',$order->user_id)->first();
            $creater_name=$creater_name->first_name.' '.$creater_name->last_name;
          }
        $data['creater_name']=$creater_name;
        $products = OrderProducts::where('order_id',$order_id)->groupBy('product_id')->get();

      $product_data = $product_variant = array();
      foreach ($products as $key => $product) {
        $product_name    = Product::where('id',$product->product_id)->value('name');
        $options         = $this->Options($product->product_id);
        $all_variants    = OrderProducts::where('order_id',$order_id)
                            ->where('product_id',$product->product_id)
                            ->pluck('product_variation_id')->toArray();
        $product_variant = $this->Variants($product->product_id,$all_variants);
        $product_data[$product->product_id] = [
          'row_id'          => $product->id,
          'order_id'     => $order_id,
          'product_id'      => $product->product_id,
          'product_name'    => $product_name,
          'options'         => $options['options'],
          'option_count'    => $options['option_count'],
          'product_variant' => $product_variant
        ];
      }
    $data['product_data']=$product_data;

    return ['data'=>$data];
    }
}
