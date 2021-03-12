<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Vendor;
use DB;
class LowStockAlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $products=Product::select('id','name','alert_quantity')->whereNotNull('alert_quantity')->get();
        $stock_details=array();
        $avalivle_low_quantity=array();
        foreach ($products as $key => $product) {
            $query=DB::table('product_variant_vendors as pvv')
                   ->leftjoin('product_variants as pv','pv.id','pvv.product_variant_id')
                   ->where('pv.product_id',$product->id)
                   ->where('pvv.stock_quantity','<=',$product->alert_quantity)
                   ->where('pv.disabled',0)
                   ->where('pv.is_deleted',0)
                   ->get();

                   foreach ($query as $key => $qq) {
                       $check_purchase_exists=DB::table('purchase as p')
                                              ->leftjoin('purchase_products as pp','p.id','pp.purchase_id')
                                              ->where('pp.product_id',$product->id)
                                              ->where('pp.product_variation_id',$qq->id)
                                              ->where('p.purchase_status','<>',2)
                                              ->exists();
                        if (!$check_purchase_exists) {
                             array_push($avalivle_low_quantity, $qq);
                        }
                   }

            $stock_details[$product->name]=$avalivle_low_quantity;
        }

        $data['stock_details']=$stock_details;
        $data['all_vendors']=[''=>'Please Select']+Vendor::where('is_deleted',0)->where('status',1)->pluck('name','id')->toArray();

        return view('admin.stock.low_stock.index',$data);
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
