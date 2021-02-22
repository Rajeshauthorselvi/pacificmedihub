<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
class VendorProdcutsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendor_id=$request->vendor_id;

        $variant_details = ProductVariantVendor::where('vendor_id',$vendor_id)
        ->groupBy('product_id')
        ->pluck('product_id')->toArray();


        $get_products = Product::where('is_deleted',0)->whereIn('id',$variant_details)
        ->orderBy('id','desc')->get();

        $products = array();

        foreach($get_products as $key => $product){
            $variant_details = ProductVariantVendor::where('product_id',$product->id)->where('display_order',1)->first();

            $total_quantity=ProductVariantVendor::where('product_id',$product->id)->sum('stock_quantity');

            $products[$key]['id'] = $product->id;
            $products[$key]['image'] = $product->main_image;
            $products[$key]['name'] = $product->name;
            $products[$key]['code'] = $product->code;
            $products[$key]['sku'] = $product->sku;
            $products[$key]['category'] = $product->category->name;
            $products[$key]['stock'] =$total_quantity;
            $products[$key]['base_price'] = isset($variant_details->base_price)?$variant_details->base_price:'-';
            $products[$key]['retail_price'] = isset($variant_details->retail_price)?$variant_details->retail_price:'-';
            $products[$key]['minimum_price'] = isset($variant_details->minimum_selling_price)?$variant_details->minimum_selling_price:'-';
            $products[$key]['published'] = $product->published;
        }
        $data['products'] = $products;
        return view('admin.vendor.product_list',$data);
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
