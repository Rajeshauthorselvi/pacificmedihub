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
         $data['purchase_products']=PurchaseProducts::with('product','optionvalue')->where('purchase_id',$id)->get();

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

        $quantity_received=$request->quantity_received;
        $issued_quantity=$request->issued_quantity;
        $reason=$request->reason;

            foreach ($quantity_received as $product_id => $option_values) {
                foreach ($option_values as $option_id => $option_valuess) {
                    foreach ($option_valuess as $op_value_id => $op_values) {

                        //var_dump($reason[$product_id][$option_id][$op_value_id]);

                    $iss_quantity=$issued_quantity[$product_id][$option_id][$op_value_id];

                    $reason_val=isset($reason[$product_id][$option_id][$op_value_id])?$reason[$product_id][$option_id][$op_value_id]:'';

                    $data=[
                        'qty_received'=>$op_values,
                        'issue_quantity'=>$iss_quantity,
                        'reason'=>$reason_val
                    ];
                   $test= PurchaseProducts::where('product_id',$product_id)
                                          ->where('purchase_id',$id)
                                          ->where('option_id',$option_id)
                                          ->where('option_value_id',$op_value_id)
                                          ->update($data);

                    }
                }
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
