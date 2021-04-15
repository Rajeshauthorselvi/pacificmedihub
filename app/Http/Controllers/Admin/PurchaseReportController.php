<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Purchase;
use App\Models\PurchaseProducts;
use App\Models\PaymentMethod;
use App\Models\PaymentHistory;
use App\Models\Vendor;
use App\Models\OrderStatus;
class PurchaseReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Purchase
      $data = array();
      $orders = array();
      $purchases = Purchase::orderBy('id','DESC')->get();
      foreach ($purchases as $key => $purchase) {
        $paid_amount=PaymentHistory::where(['ref_id'=> $purchase->id,'payment_from'=> 1])->sum('amount');
        $vendor_name      = Vendor::find($purchase->vendor_id)->name;
        $product_details  = PurchaseProducts::select(DB::raw('sum(quantity) as quantity'),
                                DB::raw('sum(sub_total) as sub_total'))->where('purchase_id',$purchase->id)->first();
        $order_status     = DB::table('order_status')->where('id',$purchase->purchase_status)->first();

        if($purchase->payment_status==1){
          $payment_status = 'Paid';
        }
        elseif($purchase->payment_status==2){
          $payment_status = 'Not Paid';
        }
        else{
          $payment_status = 'Partly Paid';
        }
        $orders[] = [
          'purchase_date'    => $purchase->purchase_date,
          'purchase_id'      => $purchase->id,
          'vendor'           => $vendor_name,
          'po_number'        => $purchase->purchase_order_number,
          'quantity'         => $product_details->quantity,
          'grand_total'      => $product_details->sub_total,
          'amount'           => $purchase->amount,
          'balance'          => ($product_details->sub_total)-($paid_amount),
          'payment_status'   => $payment_status,
          'p_status'         => $purchase->payment_status,
          'order_status'     => $order_status->status_name,
          'color_code'       => $order_status->color_codes,
          'status_id'        => $purchase->purchase_status,
          'sgd_total_amount' => $purchase->sgd_total_amount
        ];
      }
      $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)
                                ->pluck('payment_method','id')
                                    ->toArray();
     $data['order_status']=['Please Select']+OrderStatus::whereIn('id',[1,2,8])
                           ->pluck('status_name','id')->toArray();
      $data['orders']         = $orders;

      $data['all_vendors']=['Please Select']+Vendor::where('is_deleted',0)->orderBy('id','desc')->pluck('name','id')->toArray();
      return view('admin.report.purchase.index',$data);
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
        // Purchase
      $data = array();
      $orders = array();
      $purchases = DB::table('purchase');
                if ($request->filter_value!=null) {
                    $purchases->where('sgd_total_amount',$request->filter_value);
                }
                if ($request->filter_status!="0") {
                    $purchases->where('purchase_status',$request->filter_status);
                }
                if ($request->filter_vendor!="0") {
                    $purchases->where('vendor_id',$request->filter_vendor);
                }
                $purchases=$purchases->get();

      foreach ($purchases as $key => $purchase) {
        $paid_amount=PaymentHistory::where(['ref_id'=> $purchase->id,'payment_from'=> 1])->sum('amount');
        $vendor_name      = Vendor::find($purchase->vendor_id)->name;
        $product_details  = PurchaseProducts::select(DB::raw('sum(quantity) as quantity'),
                                DB::raw('sum(sub_total) as sub_total'))->where('purchase_id',$purchase->id)->first();
        $order_status     = DB::table('order_status')->where('id',$purchase->purchase_status)->first();

        if($purchase->payment_status==1){
          $payment_status = 'Paid';
        }
        elseif($purchase->payment_status==2){
          $payment_status = 'Not Paid';
        }
        else{
          $payment_status = 'Partly Paid';
        }
        $orders[] = [
          'purchase_date'    => $purchase->purchase_date,
          'purchase_id'      => $purchase->id,
          'vendor'           => $vendor_name,
          'po_number'        => $purchase->purchase_order_number,
          'quantity'         => $product_details->quantity,
          'grand_total'      => $product_details->sub_total,
          'amount'           => $purchase->amount,
          'balance'          => ($product_details->sub_total)-($paid_amount),
          'payment_status'   => $payment_status,
          'p_status'         => $purchase->payment_status,
          'order_status'     => $order_status->status_name,
          'color_code'       => $order_status->color_codes,
          'status_id'        => $purchase->purchase_status,
          'sgd_total_amount' => $purchase->sgd_total_amount
        ];
      }
      $data['payment_method'] = [''=>'Please Select']+PaymentMethod::where('status',1)
                                ->pluck('payment_method','id')
                                    ->toArray();
     $data['order_status']=['Please Select']+OrderStatus::whereIn('id',[1,2,8])
                           ->pluck('status_name','id')->toArray();
      $data['orders']         = $orders;

      $data['all_vendors']=['Please Select']+Vendor::where('is_deleted',0)->orderBy('id','desc')->pluck('name','id')->toArray();
      return view('admin.report.purchase.index',$data);
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
        //
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
}
