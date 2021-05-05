<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Product;
use App\Models\Option;
class StockListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $get_products = Product::where('is_deleted',0)->orderBy('id','desc')->get();
        $products = array();
        foreach($get_products as $key => $product){
            $variant_details = ProductVariantVendor::where('product_id',$product->id)->orderBy('id','desc')->first();
            $total_quantity = ProductVariantVendor::where('product_id',$product->id)->sum('stock_quantity');
            $products[$key]['id'] = $product->id;
            $products[$key]['image'] = $product->main_image;
            $products[$key]['name'] = $product->name;
            $products[$key]['code'] = $product->code;
            /*$products[$key]['sku'] = $product->sku;*/
            $products[$key]['category'] = $product->category->name;
            $products[$key]['stock'] =$total_quantity;
            $products[$key]['base_price'] = isset($variant_details->base_price)?$variant_details->base_price:'-';
            $products[$key]['retail_price'] = isset($variant_details->retail_price)?$variant_details->retail_price:'-';
            $products[$key]['minimum_price'] = isset($variant_details->minimum_selling_price)?$variant_details->minimum_selling_price:'-';
            $products[$key]['published'] = $product->published;
        }
        $data['products'] = $products;
        return view('admin.stock.stock_list.index',$data);
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
        $data['product'] = Product::find($id);

        $options = array();
        $options_id = array();

        $vendor_id = ProductVariantVendor::select('vendor_id')
                    ->where('product_id',$id)
                    ->distinct()
                    ->pluck('vendor_id')
                    ->toArray();
        $data['product'] = Product::find($id);
        $variant = ProductVariant::where('product_id',$id)->where('is_deleted',0)->first();
        $options = array();
        $options_id = array();

        $vendor_id = ProductVariantVendor::select('vendor_id')->where('product_id',$id)->distinct()->pluck('vendor_id')->toArray();
       $variant = ProductVariant::where('product_id',$id)
                  ->where('disabled',0)
                  ->where('is_deleted',0)
                  ->first();
       
        $options_val=$this->Options($variant,$vendor_id);

        $option_count=$options_val['option_count'];
        $options=$options_val['options'];
        $options_id=$options_val['options_id'];

        $data['option_count'] = $option_count;
        $data['get_options'] = $options;
        $data['options_id'] = $options_id;
        $data['vendors_id'] = $vendor_id;

        $product_variants=$this->Variants($id);
        $data['product_variant'] =$product_variants;

        $data['product_options'] = Option::where('published',1)->where('is_deleted',0)->orderBy('display_order','asc')->pluck('option_name','id')->toArray();
       
       return view('admin.stock.stock_list.stock_list_modal',$data);
    }

    public function Variants($product_id=0,$type='')
    {
        //dd($type);
        
         if ($type!="") {
            $variant         = ProductVariant::where('product_id',$product_id)
                                ->where('is_deleted',0)
                                ->where('disabled',1)
                                ->first();
            $productVariants = ProductVariant::where('product_id',$product_id)
                                ->where('is_deleted',0)
                                ->where('disabled',1)
                                ->get();
            $variant_vendors=ProductVariantVendor::where('product_id',$product_id)->where('display_variant',0)->get();
         }
         else{
            $variant = ProductVariant::where('product_id',$product_id)
                       ->where('is_deleted',0)
                       ->where('disabled',0)
                       ->first();

            $productVariants = ProductVariant::where('product_id',$product_id)
                                ->where('disabled',0)
                               ->where('is_deleted',0)
                               ->get();
            $variant_vendors=ProductVariantVendor::where('product_id',$product_id)->where('display_variant',1)->get();
         }

        foreach ($variant_vendors as $key => $variant_vendor) {
            if ($type!="") {
                $variants=ProductVariant::where('id',$variant_vendor->product_variant_id)
                          ->where('is_deleted',0)
                          ->where('disabled',1)->first();
            }
            else{
                $variants=ProductVariant::where('id',$variant_vendor->product_variant_id)
                          ->where('is_deleted',0)->where('disabled',0)->first();
            }

            // $variant_details = ProductVariantVendor::where('product_id',$variants->product_id)->where('product_variant_id',$variants->id)->first();

            $product_variants[$key]['row_id'] = $variant_vendor->id;
            $product_variants[$key]['variant_id'] = $variants->id;
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
                $product_variants[$key]['option_id2'] = $variants->option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->option_value_id2;
                $product_variants[$key]['option_value2'] = $variants->optionValue2->option_value;
                $product_variants[$key]['option_id3'] = $variants->option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->option_value_id3;
                $product_variants[$key]['option_value3'] = $variants->optionValue3->option_value;
            }
            elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5==NULL))
            {
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
                $product_variants[$key]['option_id2'] = $variants->option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->option_value_id2;
                $product_variants[$key]['option_value2'] = $variants->optionValue2->option_value;
                $product_variants[$key]['option_id3'] = $variants->option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->option_value_id3;
                $product_variants[$key]['option_value3'] = $variants->optionValue3->option_value;
                $product_variants[$key]['option_id4'] = $variants->option_id4;
                $product_variants[$key]['option_value_id4'] = $variants->option_value_id4;
                $product_variants[$key]['option_value4'] = $variants->optionValue4->option_value;
            }
            elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5!=NULL))
            {
                $product_variants[$key]['option_id1'] = $variants->option_id;
                $product_variants[$key]['option_value_id1'] = $variants->option_value_id;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
                $product_variants[$key]['option_id2'] = $variants->option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->option_value_id2;
                $product_variants[$key]['option_value2'] = $variants->optionValue2->option_value;
                $product_variants[$key]['option_id3'] = $variants->option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->option_value_id3;
                $product_variants[$key]['option_value3'] = $variants->optionValue3->option_value;
                $product_variants[$key]['option_id4'] = $variants->option_id4;
                $product_variants[$key]['option_value_id4'] = $variants->option_value_id4;
                $product_variants[$key]['option_value4'] = $variants->optionValue4->option_value;
                $product_variants[$key]['option_id5'] = $variants->option_id5;
                $product_variants[$key]['option_value_id5'] = $variants->option_value_id5;
                $product_variants[$key]['option_value5'] = $variants->optionValue5->option_value;
            }
            $product_variants[$key]['sku'] = isset($variant_vendor->sku)?$variant_vendor->sku:'';

                $product_variants[$key]['base_price'] = isset($variant_vendor->base_price)?$variant_vendor->base_price:'';
                $product_variants[$key]['retail_price'] = isset($variant_vendor->retail_price)?$variant_vendor->retail_price:'';
                $product_variants[$key]['minimum_selling_price'] = isset($variant_vendor->minimum_selling_price)?$variant_vendor->minimum_selling_price:'';
                $product_variants[$key]['display_order'] = isset($variant_vendor->display_order)?$variant_vendor->display_order:'';
                $product_variants[$key]['stock_quantity'] = isset($variant_vendor->stock_quantity)?$variant_vendor->stock_quantity:'';
                $product_variants[$key]['display_variant'] = isset($variant_vendor->display_variant)?$variant_vendor->display_variant:'';
                $product_variants[$key]['vendor_id'] = isset($variant_vendor->vendor_id)?$variant_vendor->vendor_id:'';
                $product_variants[$key]['vendor_name'] = isset($variant_vendor->vendorName->name)?$variant_vendor->vendorName->name:'';
        }
            
        return $product_variants;
    }

    public function Options($variant,$vendor_id)
    {

        if(($variant->option_id!=NULL)&&($variant->option_id2==NULL)&&($variant->option_id3==NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $option_count = 1;
            $options_id [] = $variant->option_id;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3==NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options_id [] = $variant->option_id;
            $options_id [] = $variant->option_id2;
            $option_count = 2;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4==NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $options_id [] = $variant->option_id;
            $options_id [] = $variant->option_id2;
            $options_id [] = $variant->option_id3;
            $option_count = 3;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5==NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $options[] = $variant->optionName4->option_name;
            $options_id [] = $variant->option_id;
            $options_id [] = $variant->option_id2;
            $options_id [] = $variant->option_id3;
            $options_id [] = $variant->option_id4;
            $option_count = 4;
        }
        elseif(($variant->option_id!=NULL)&&($variant->option_id2!=NULL)&&($variant->option_id3!=NULL)&&($variant->option_id4!=NULL)&&($variant->option_id5!=NULL))
        {
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $options[] = $variant->optionName4->option_name;
            $options_id [] = $variant->option_id;
            $options_id [] = $variant->option_id2;
            $options_id [] = $variant->option_id3;
            $options_id [] = $variant->option_id4;
            $options_id [] = $variant->option_id5;
            $option_count = 5;
        }


        return ['option_count'=>$option_count,'options'=>$options,'options_id'=>$options_id];
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
