<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;
use App\Models\Vendor;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\PurchaseProducts;
use App\Models\Option;
use App\Models\OptionValue;
use App\Models\Prefix;
use App\Models\RFQ;
use App\Models\RFQProducts;
use App\Models\CommissionValue;
use App\Models\Countries;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use Redirect;
use DB;
use Session;
use Response;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('product','read')) {
                abort(404);
            }
        }
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
        return view('admin/products/index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('product','create')) {
                abort(404);
            }
        }
        if ($request->has('from') && $request->has('vendor_id')) {
            Session::put('active_vendor','yes');
            Session::put('vendor_id',$request->get('vendor_id'));
        }
        $data['categories']      = Categories::where('published',1)->where('is_deleted',0)->orderBy('name','asc')
                                        ->get();
        $data['brands']          = Brand::where('published',1)->where('is_deleted',0)->orderBy('name','asc')->get();
        $data['vendors']         = Vendor::where('status',1)->where('is_deleted',0)->orderBy('name','asc')->get();
        $data['product_options'] = Option::where('published',1)->where('is_deleted',0)->orderBy('display_order','asc')
                                        ->get();
        //Commission Id 2 is a Product Commission
        $data['commissions']     = CommissionValue::where('commission_id',2)->where('published',1)
                                        ->where('is_deleted',0)->get();
        $data['product_id']      = '';
        $product_code = Prefix::where('key','prefix')->where('code','product_code')->value('content');
        if (isset($product_code)) {
            $value = unserialize($product_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Product::count();
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
            $data['product_id']=$replace_number;
        }

        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        $data['display_order']=Categories::where('is_deleted',0)->orderBy('id','desc')->take(1)->value('display_order');
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/products/create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'product_name'  => 'required',
            'category'      => 'required',
            'search_engine' => 'required'
        ]);

        if($request->published){$published = 1;}else{$published = 0;}
        if($request->homepage){$homepage = 1;}else{$homepage = 0;}

        $image = $request->hasFile('main_image');
        if($image){
            $photo          = $request->file('main_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('main_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->main_image->move(public_path('theme/images/products/main/'), $image_name);
        }
        else{
            $image_name = NULL;
        }

        $product = new Product();
        $product->name = $request->product_name;
        $product->code = $request->product_code;
        $product->sku = $request->product_sku;

        $product->category_id = $request->category;
        $product->brand_id = $request->brand;
        $product->main_image = $image_name;
        $product->short_description = $request->short_description;
        $product->long_description = $request->product_details;
        $product->alert_quantity = $request->alert_qty;
        $product->commission_type = $request->commision_type;
        $product->commission_value = $request->commision_value;
        $product->published = $published;
        $product->show_home = $homepage;
        $product->search_engine_name = $request->search_engine;
        $product->meta_title = $request->meta_title;
        $product->meta_keyword = $request->meta_keyword;
        $product->meta_description = $request->meta_description;
        $product->created_at =  date('Y-m-d H:i:s');
        $product->save();
            
        $allvariants=$request->variant_data;
        $product_id=$product->id;

        $key=0;
        foreach ($allvariants as $vendor_id => $total_variant) {
                if ($key==0) {
                    foreach ($total_variant['option_value_id1'] as $data => $variants) {
                            $variant_id=ProductVariant::insertGetId([
                                'product_id'        => $product_id,
                                'option_id'         => $total_variant['option_id1'][$data],
                                'option_value_id'   => $total_variant['option_value_id1'][$data],
                                'option_id2'        => isset($total_variant['option_id2'][$data])?$total_variant['option_id2'][$data]:'',
                                'option_value_id2'  => isset($total_variant['option_value_id2'][$data])?$total_variant['option_value_id2'][$data]:'',
                                'option_id3'        => isset($total_variant['option_id3'][$data])?$total_variant['option_id3'][$data]:'',
                                'option_value_id3'  => isset($total_variant['option_value_id3'][$data])?$total_variant['option_value_id3'][$data]:'',
                                'option_id4'        => isset($total_variant['option_id4'][$data])?$total_variant['option_id4'][$data]:'',
                                'option_value_id4'  => isset($total_variant['option_value_id4'][$data])?$total_variant['option_value_id4'][$data]:'',
                                'option_id5'        => isset($total_variant['option_id5'][$data])?$total_variant['option_id5'][$data]:'',
                                'option_value_id5'  => isset($total_variant['option_value_id5'][$data])?$total_variant['option_value_id5'][$data]:'',
                                'is_deleted'        => 0
                            ]); 
                        ProductVariantVendor::insert([
                            'product_id'            => $product_id,
                            'product_variant_id'    => $variant_id,
                            'base_price'            => $total_variant['base_price'][$data],
                            'retail_price'          => $total_variant['retail_price'][$data],
                            'minimum_selling_price' => $total_variant['minimum_price'][$data],
                            'display_variant'       => $total_variant['display'][$data],
                            'display_order'         => $total_variant['display_order'][$data],
                            'stock_quantity'        => $total_variant['stock_qty'][$data],
                            'sku'                   => $total_variant['sku'][$data],
                            'vendor_id'             => $vendor_id,
                        ]); 
                    }
                }
                else{
                    foreach ($total_variant['option_value_id1'] as $data => $variants) {

                        $variant_id=ProductVariant::where(['product_id'=> $product_id, 'option_id' => $total_variant['option_id1'][$data], 'option_value_id'   => $total_variant['option_value_id1'][$data], 'option_id2'=> isset($total_variant['option_id2'][$data])?$total_variant['option_id2'][$data]:'', 'option_value_id2'  => isset($total_variant['option_value_id2'][$data])?$total_variant['option_value_id2'][$data]:'', 'option_id3'=> isset($total_variant['option_id3'][$data])?$total_variant['option_id3'][$data]:'', 'option_value_id3'  => isset($total_variant['option_value_id3'][$data])?$total_variant['option_value_id3'][$data]:'', 'option_id4'=> isset($total_variant['option_id4'][$data])?$total_variant['option_id4'][$data]:'', 'option_value_id4'  => isset($total_variant['option_value_id4'][$data])?$total_variant['option_value_id4'][$data]:'', 'option_id5'=> isset($total_variant['option_id5'][$data])?$total_variant['option_id5'][$data]:'', 'option_value_id5'  => isset($total_variant['option_value_id5'][$data])?$total_variant['option_value_id5'][$data]:''])
                            ->first();

                            ProductVariantVendor::insert([
                                'product_id'            => $product_id,
                                'product_variant_id'    => $variant_id->id,
                                'base_price'            => $total_variant['base_price'][$data],
                                'retail_price'          => $total_variant['retail_price'][$data],
                                'minimum_selling_price' => $total_variant['minimum_price'][$data],
                                'display_variant'       => $total_variant['display'][$data],
                                'display_order'         => $total_variant['display_order'][$data],
                                'stock_quantity'        => $total_variant['stock_qty'][$data],
                                'vendor_id'             => $vendor_id,
                                'sku'                   => $total_variant['sku'][$data],
                            ]);   
                    }
                }
            $key++;
        }

            if($request->hasfile('images')){
                $gal_images = $request->file('images');
                $image_name = [];
                $i=0;
                foreach ($gal_images as $img) { 
                    $photo          = $img;            
                    $filename       = $photo->getClientOriginalName();            
                    $file_extension = $img->getClientOriginalExtension();
                    $img->move(public_path('theme/images/products/'), $filename);
                    $image_name[$i]['photos'] = $filename;
                    $i = $i + 1;
                }
                $s=1;
                foreach ($image_name as $value) {
                    $addImage = new ProductImage();
                    $addImage->product_id = $product->id;
                    $addImage->name = $value['photos'];
                    $addImage->display_order = $s++;
                    $addImage->timestamps = false;
                    $addImage->save();
                }
            }

            if (Session::get('active_vendor')=="yes") {
                $vendor_id=Session::has('vendor_id');
                Session::forget('vendor_id');
                Session::forget('active_vendor');
                return Redirect::route('vendor-products.index',['vendor_id'=>$vendor_id])->with('success','New Product Added successfully...!');
            }
            return redirect()->route('products.index')->with('success','New Product Added successfully...!');
        
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
       //dd($variant);
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

        $old_variant = ProductVariant::where('product_id',$id)
                  ->where('disabled',1)
                  ->where('is_deleted',0)
                  ->first();

        $data['old_product_variant']=array();
        if (isset($old_variant)) {
            $old_product_variants=$this->Variants($id,'old');
            $old_options_val=$this->Options($old_variant,$vendor_id);

            $option_count=$old_options_val['option_count'];
            $options=$old_options_val['options'];
            $options_id=$old_options_val['options_id'];;


            $data['old_option_count'] = $option_count;
            $data['old_options'] = $options;
            $data['old_options_id'] = $options_id;
            $data['old_vendors_id'] = $vendor_id;
            $data['old_product_variant'] = $old_product_variants;
        }



        $data['product_images']  = ProductImage::where('product_id',$id)->where('is_deleted',0)->get();
        $data['categories']      = Categories::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['brands']          = Brand::where('published',1)->where('is_deleted',0)->orderBy('name','asc')->get();
        $data['vendors']         = Vendor::where('is_deleted',0)->orderBy('name','asc')->pluck('name','id')->toArray();
        $data['product_options'] = Option::where('published',1)->where('is_deleted',0)->orderBy('display_order','asc')
                                        ->pluck('option_name','id')->toArray();
        //Commission Id 2 is a Product Commission
        $data['commissions']     = CommissionValue::where('commission_id',2)->where('published',1)
                                        ->where('is_deleted',0)->get();
        $data['order_exists']    = PurchaseProducts::where('product_id',$id)->exists();
        return view('admin/products/show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('product','update')) {
                abort(404);
            }
        }
        if ($request->has('from') && $request->has('vendor_id')) {
            Session::put('active_vendor','yes');
            Session::put('vendor_id',$request->get('vendor_id'));
        }

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

        // dd($data);
        $product_variants=$this->Variants($id);

        $data['product_variant'] =$product_variants;
        Session::put('existing_vendors',$vendor_id);

        $old_variant = ProductVariant::where('product_id',$id)
                  ->where('disabled',1)
                  ->where('is_deleted',0)
                  ->first();

        $data['old_product_variant']=array();
        if (isset($old_variant)) {
            $old_product_variants=$this->Variants($id,'old');
            $old_options_val=$this->Options($old_variant,$vendor_id);

            $option_count=$old_options_val['option_count'];
            $options=$old_options_val['options'];
            $options_id=$old_options_val['options_id'];;

            $data['old_option_count'] = $option_count;
            $data['old_options'] = $options;
            $data['old_options_id'] = $options_id;
            $data['old_vendors_id'] = $vendor_id;
            $data['old_product_variant'] = $old_product_variants;
        }

        $data['product_images']  = ProductImage::where('product_id',$id)->where('is_deleted',0)->get();
        $data['categories']      = Categories::where('published',1)->where('is_deleted',0)->orderBy('name','asc')
                                        ->get();
        $data['brands']          = Brand::where('published',1)->where('is_deleted',0)->orderBy('name','asc')->get();
        $data['vendors']         = Vendor::where('status',1)->where('is_deleted',0)->orderBy('name','asc')
                                        ->pluck('name','id')->toArray();
        $data['product_options'] = Option::where('published',1)->where('is_deleted',0)->orderBy('display_order','asc')
                                        ->pluck('option_name','id')->toArray();
        //Commission Id 2 is a Product Commission
        $data['commissions']     = CommissionValue::where('commission_id',2)->where('published',1)
                                        ->where('is_deleted',0)->get();
        $data['order_exists']    = PurchaseProducts::where('product_id',$id)->exists();
        //dd($data);
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        $data['display_order']=Categories::where('is_deleted',0)->orderBy('id','desc')->take(1)->value('display_order');
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin/products/edit',$data);
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


         $this->validate(request(), [
            'product_name' => 'required',
            'product_code' => 'required',
            'category'     => 'required',
            'search_engine' => 'required'
        ]);
        $product_id=$id;

        $product = Product::where('id',$id)->first();

        $purchase_exists=PurchaseProducts::where('product_id',$product->id)->exists();
        $rfq_exists=RFQProducts::where('product_id',$product->id)->exists();


        if ($request->has('existOption')) {
            $existOption = json_decode($request->existOption,true);
            $diff_options=array_diff($options, $existOption);
        }

        if ($request->has('existVendor')) {
            $existing_vendor=json_decode($request->existVendor,true);
            $diff_vendor=array_diff($vendors, $existing_vendor);
        }

        if (!$purchase_exists && !$rfq_exists) {
                if ($request->has('new_variant')) {
                    ProductVariant::where('product_id',$id)->delete();
                    ProductVariantVendor::where('product_id',$id)->delete();
                }
           
        }elseif($purchase_exists || $rfq_exists){
            $product_variants = ProductVariant::where('product_id',$id)->get();

            if ((isset($diff_vendor) || isset($diff_options))) {
                foreach ($product_variants as $key => $value) {
                    ProductVariant::where('id',$value->id)->update(['disabled'=>1]);
                }
            }

        }

        if($request->published){$published = 1;}else{$published = 0;}
        if($request->homepage){$homepage = 1;}else{$homepage = 0;}

        $image = $request->hasFile('main_image');
        if($image){
            $photo          = $request->file('main_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('main_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->main_image->move(public_path('theme/images/products/main/'), $image_name);
        }
        else{
            $image_name = $product->main_image;
        }

        $product->name = $request->product_name;
        $product->code = $request->product_code;
        // $product->sku = $request->product_sku;
        $product->category_id = $request->category;
        $product->brand_id = $request->brand;
        $product->main_image = $image_name;
        $product->short_description = $request->short_description;
        $product->long_description = $request->product_details;
        /*$product->treatment_information = $request->treatment_information;
        $product->dosage_instructions = $request->dosage_instructions;*/
        $product->alert_quantity = $request->alert_qty;
        $product->commission_type = $request->commision_type;
        $product->commission_value = $request->commision_value;
        $product->published = $published;
        $product->show_home = $homepage;
        $product->search_engine_name = $request->search_engine;
        $product->meta_title = $request->meta_title;
        $product->meta_keyword = $request->meta_keyword;
        $product->meta_description = $request->meta_description;
        $product->update();

        if ($request->has('variant_data')) {
            $this->AddNewVariants($request->variant_data,$product->id);
        }
        
        if ($request->has('new_variant')) {
            if ((isset($diff_vendor) && isset($diff_options))) {
                ProductVariant::where('product_id',$product_id)->update(['disabled'=>1]);
                ProductVariantVendor::where('product_id',$product_id)->update(['display_variant'=>0]);
            }
            $this->AddNewVariants($request->new_variant,$product->id);
            return redirect()->route('products.index')->with('success','Product modified successfully...!');
        }

        if($product->id){
            if($request->variant){

                $variant_data = [];

                if(isset($request->variant['variant_id'])){
                        $i = 0;
                    foreach ($request->variant['variant_id'] as $id) {
                        $variant_data[$i]['variant_id'] = $id;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['variant_id'] = null;
                        $i = $i + 1;
                    }
                }
                if(isset($request->variant['id'])){
                        $i = 0;
                    foreach ($request->variant['id'] as $id) {
                        $variant_data[$i]['id'] = $id;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['id'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_id1'])){
                        $i = 0;
                    foreach ($request->variant['option_id1'] as $option_id1) {
                        $variant_data[$i]['option_id1'] = $option_id1;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_id1'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_id2'])){
                        $i = 0;
                    foreach ($request->variant['option_id2'] as $option_id2) {
                        $variant_data[$i]['option_id2'] = $option_id2;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_id2'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_id3'])){
                        $i = 0;
                    foreach ($request->variant['option_id3'] as $option_id3) {
                        $variant_data[$i]['option_id3'] = $option_id3;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_id3'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_id4'])){
                        $i = 0;
                    foreach ($request->variant['option_id4'] as $option_id4) {
                        $variant_data[$i]['option_id4'] = $option_id4;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_id4'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_id5'])){
                        $i = 0;
                    foreach ($request->variant['option_id5'] as $option_id5) {
                        $variant_data[$i]['option_id5'] = $option_id5;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_id5'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_value_id1'])){
                        $i = 0;
                    foreach ($request->variant['option_value_id1'] as $option_value_id1) {
                        $variant_data[$i]['option_value_id1'] = $option_value_id1;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_value_id1'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_value_id2'])){
                        $i = 0;
                    foreach ($request->variant['option_value_id2'] as $option_value_id2) {
                        $variant_data[$i]['option_value_id2'] = $option_value_id2;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_value_id2'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_value_id3'])){
                        $i = 0;
                    foreach ($request->variant['option_value_id3'] as $option_value_id3) {
                        $variant_data[$i]['option_value_id3'] = $option_value_id3;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_value_id3'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_value_id4'])){
                        $i = 0;
                    foreach ($request->variant['option_value_id4'] as $option_value_id4) {
                        $variant_data[$i]['option_value_id4'] = $option_value_id4;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_value_id4'] = null;
                        $i = $i + 1;
                    }
                }

                if(isset($request->variant['option_value_id5'])){
                        $i = 0;
                    foreach ($request->variant['option_value_id5'] as $option_value_id5) {
                        $variant_data[$i]['option_value_id5'] = $option_value_id5;
                        $i = $i + 1;
                    }
                }else{
                    $i = 0;
                    foreach ($request->variant['base_price'] as $base_price) {
                        $variant_data[$i]['option_value_id5'] = null;
                        $i = $i + 1;
                    }
                }

                $i = 0;
                foreach ($request->variant['sku'] as $sku) {
                    $variant_data[$i]['sku'] = $sku;
                    $i = $i + 1;
                }

                $i = 0;
                foreach ($request->variant['base_price'] as $base_price) {
                    $variant_data[$i]['base_price'] = $base_price;
                    $i = $i + 1;
                }
                $i = 0;
                foreach ($request->variant['retail_price'] as $retail_price) {
                    $variant_data[$i]['retail_price'] = $retail_price;
                    $i = $i + 1;
                }
                $i = 0;
                foreach ($request->variant['minimum_price'] as $minimum_price) {
                    $variant_data[$i]['minimum_price'] = $minimum_price;
                    $i = $i + 1;
                }
                $i = 0;
                foreach ($request->variant['stock_qty'] as $stock_qty) {
                    $variant_data[$i]['stock_qty'] = $stock_qty;
                    $i = $i + 1;
                }
              
                $i = 0;
                foreach ($request->variant['vendor_id'] as $vendor_id) {
                    $variant_data[$i]['vendor_id'] = $vendor_id;
                    $i = $i + 1;
                }

                $i = 0;
                foreach ($request->variant['display_order'] as $display_order) {
                    $variant_data[$i]['display_order'] = $display_order;
                    $i = $i + 1;
                }

                $i = 0;
                foreach ($request->variant['display'] as $display) {
                    $variant_data[$i]['display'] = $display;
                    $i = $i + 1;
                }


                
                $variants=$request->variant;

                $base_price=$variants['base_price'];
                $retail_price=$variants['retail_price'];
                $minimum_price=$variants['minimum_price'];
                $display_order=$variants['display_order'];
                $stock_qty=$variants['stock_qty'];
                $vendor_id=$variants['vendor_id'];
                $display_variant=$variants['display'];

                foreach ($variants['id'] as $key => $variant_vendor_id) {

                            ProductVariantVendor::where('id',$variant_vendor_id)
                            ->update([
                                'base_price' => $base_price[$key],
                                'retail_price' => $retail_price[$key],
                                'minimum_selling_price' => $minimum_price[$key],
                                'display_variant' => $display_variant[$key],
                                'display_order' => $display_order[$key],
                                'stock_quantity' => $stock_qty[$key],
                                'vendor_id' => $vendor_id[$key],
                            ]);  
                }
/*
                exit();

                foreach ($variant_data as $key=>$value) {
                    $update_variant = ProductVariant::find($value['variant_id']);
                    if($update_variant!=null)
                    {
                        $update_variant->product_id = $product_id;
                        $update_variant->sku = $value['sku'];
                        $update_variant->option_id = $value['option_id1'];
                        $update_variant->option_value_id = $value['option_value_id1'];
                        $update_variant->option_id2 = isset($value['option_id2'])?$value['option_id2']:null;
                        $update_variant->option_value_id2 = isset($value['option_value_id2'])?$value['option_value_id2']:null;
                        $update_variant->option_id3 = isset($value['option_id3'])?$value['option_id3']:null;
                        $update_variant->option_value_id3 = isset($value['option_value_id3'])?$value['option_value_id3']:null;
                        $update_variant->option_id4 = isset($value['option_id4'])?$value['option_id4']:null;
                        $update_variant->option_value_id4 = isset($value['option_value_id4'])?$value['option_value_id4']:null;
                        $update_variant->option_id5 = isset($value['option_id5'])?$value['option_id5']:null;
                        $update_variant->option_value_id5 = isset($value['option_value_id5'])?$value['option_id5']:null;
                        $update_variant->timestamps = false;
                        $update_variant->save();
                        
                        if($update_variant){

                            ProductVariantVendor::where('product_variant_id',$value['id'])
                            ->update([
                                'product_id' => $product_id,
                                'product_variant_id' => $update_variant->id,
                                'base_price' => $value['base_price'],
                                'retail_price' => $value['retail_price'],
                                'minimum_selling_price' => $value['minimum_price'],
                                'display_variant' => $value['display'],
                                'display_order' => $value['display_order'],
                                'stock_quantity' => $value['stock_qty'],
                                'vendor_id' => $value['vendor_id'],
                                'sku' => $value['sku'],
                            ]);

                        }
                    }
                    elseif($update_variant==null)
                    {   
                        $add = new ProductVariant();
                        $add->product_id = $product->id;
                        $add->sku        = $value['sku'];
                        $add->option_id = $value['option_id1'];
                        $add->option_value_id = $value['option_value_id1'];
                        $add->option_id2 = isset($value['option_id2'])?$value['option_id2']:null;
                        $add->option_value_id2 = isset($value['option_value_id2'])?$value['option_value_id2']:null;
                        $add->option_id3 = isset($value['option_id3'])?$value['option_id3']:null;
                        $add->option_value_id3 = isset($value['option_value_id3'])?$value['option_value_id3']:null;
                        $add->option_id4 = isset($value['option_id4'])?$value['option_id4']:null;
                        $add->option_value_id4 = isset($value['option_value_id4'])?$value['option_value_id4']:null;
                        $add->option_id5 = isset($value['option_id5'])?$value['option_id5']:null;
                        $add->option_value_id5 = isset($value['option_value_id5'])?$value['option_id5']:null;
                        $add->created_at = date('Y-m-d H:i:s');
                        $add->save();

                        if($add){
                            $vendor = new ProductVariantVendor();
                            $vendor->product_id = $product->id;
                            $vendor->product_variant_id = $add->id;
                            $vendor->base_price = $value['base_price'];
                            $vendor->retail_price = $value['retail_price'];
                            $vendor->minimum_selling_price = $value['minimum_price'];
                            $vendor->display_variant = $value['display'];
                            $vendor->display_order = $value['display_order'];
                            $vendor->stock_quantity = $value['stock_qty'];
                            $vendor->vendor_id = $value['vendor_id'];
                            $vendor->timestamps = false;
                            $vendor->save();
                        }
                    }
                }*/
            }

            if($request->has("removed_vendor")){
                $removed_vendor = $request->removed_vendor['id'];
                $remove_product_variants = ProductVariantVendor::whereIn('vendor_id',$removed_vendor)->get();
                foreach($remove_product_variants as $key => $value) {
                    $check_purchase_exists=PurchaseProducts::where('product_id',$value->product_id)->exists();
                    $check_rfq_exists=RFQProducts::where('product_id',$value->product_id)->exists();
                    if(!$check_rfq_exists && !$check_purchase_exists){
                        $delete_variant = DB::table('product_variants')->where('id',$value->id)->delete();
                        $delete_variant_vendor = DB::table('product_variant_vendors')->where('vendor_id',$value->vendor_id)->delete();
                    }else{
                        ProductVariant::where('id',$value->product_variant_id)->update(['disabled'=>1]);
                        
                        $disable_variant_vendor = ProductVariantVendor::where('vendor_id',$value->vendor_id)->get();
                        foreach ($disable_variant_vendor as $key => $vendorValue) {
                            $get_disable_vendor = ProductVariantVendor::where('id',$vendorValue->id)->first();
                            $get_disable_vendor->display_variant = 0;
                            $get_disable_vendor->update();
                        }
                    }
                }
            }

            if($request->hasfile('images')){
                $gal_images = $request->file('images');
                $image_name = [];
                $i=0;
                foreach ($gal_images as $img) { 
                    $photo          = $img;            
                    $filename       = $photo->getClientOriginalName();            
                    $file_extension = $img->getClientOriginalExtension();
                    $img->move(public_path('theme/images/products/'), $filename);
                    $image_name[$i]['photos'] = $filename;
                    $i = $i + 1;
                }
                $s=1;
                foreach ($image_name as $value) {
                    $addImage = new ProductImage();
                    $addImage->product_id = $product->id;
                    $addImage->name = $value['photos'];
                    $addImage->display_order = $s++;
                    $addImage->timestamps = false;
                    $addImage->save();
                }
            }
            if (Session::get('active_vendor')=="yes") {
                $vendor_id=Session::has('vendor_id');
                Session::forget('vendor_id');
                Session::forget('active_vendor');
                return Redirect::route('vendor-products.index',['vendor_id'=>$vendor_id])->with('success','Product modified successfully...!');
            }
            return redirect()->route('products.index')->with('success','Product modified successfully...!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!'); 
        }
    }


    public function AddNewVariants($variants,$product_id)
    {


/*        ProductVariant::where('product_id',$product_id)->update(['disabled'=>1]);
        ProductVariantVendor::where('product_id',$product_id)->update(['display_variant'=>0]);

        $ids=$variants['id'];
        $option_id1=isset($variants['option_id1'])?$variants['option_id1']:'';
        $option_value_id1=isset($variants['option_value_id1'])?$variants['option_value_id1']:'';
        $option_id2=isset($variants['option_id2'])?$variants['option_id2']:'';
        $option_value_id2=isset($variants['option_value_id2'])?$variants['option_value_id2']:'';
        $option_id3=isset($variants['option_id3'])?$variants['option_id3']:'';
        $option_value_id3=isset($variants['option_value_id3'])?$variants['option_value_id3']:'';
        $option_id4=isset($variants['option_id4'])?$variants['option_id4']:'';
        $option_value_id4=isset($variants['option_value_id4'])?$variants['option_value_id4']:'';
        $option_id5=isset($variants['option_id5'])?$variants['option_id5']:'';
        $option_value_id5=isset($variants['option_value_id5'])?$variants['option_value_id5']:'';

        $sku = $variants['sku'];

        $base_price=$variants['base_price'];
        $retail_price=$variants['retail_price'];
        $minimum_price=$variants['minimum_price'];
        $stock_qty=$variants['stock_qty'];
        $vendor_id=$variants['vendor_id'];
        $display_order=$variants['display_order'];
        $display=$variants['display'];

        foreach ($ids as $key => $id) {
            $add = new ProductVariant();
            $add->product_id = $product_id;
            $add->sku = $sku[$key];
            $add->option_id = $option_id1[$key];
            $add->option_value_id = $option_value_id1[$key];

            $add->option_id2 = isset($option_id2[$key])?$option_id2[$key]:'';
            $add->option_value_id2 = isset($option_value_id2[$key])?$option_value_id2[$key]:'';

            $add->option_id3 = isset($option_id3[$key])?$option_id3[$key]:'';
            $add->option_value_id3 = isset($option_value_id3[$key])?$option_value_id3[$key]:'';

            $add->option_id4 = isset($option_id4[$key])?$option_id4[$key]:'';
            $add->option_value_id4 = isset($option_value_id4[$key])?$option_value_id4[$key]:'';

            $add->option_id5 = isset($option_id5[$key])?$option_id5[$key]:'';
            $add->option_value_id5 = isset($option_value_id5[$key])?$option_value_id5[$key]:'';
            $add->created_at = date('Y-m-d H:i:s');
            $add->save();

            if($add){
                $vendor = new ProductVariantVendor();
                $vendor->product_id = $product_id;
                $vendor->product_variant_id = $add->id;
                $vendor->base_price = $base_price[$key];
                $vendor->retail_price = $retail_price[$key];
                $vendor->minimum_selling_price = $minimum_price[$key];
                $vendor->display_variant = $display[$key];
                $vendor->display_order = $display_order[$key];
                $vendor->stock_quantity = $stock_qty[$key];
                $vendor->vendor_id = $vendor_id[$key];
                $vendor->timestamps = false;
                $vendor->save();
            }
        }*/

        $key=0;
        foreach ($variants as $vendor_id => $total_variant) {
                if ($key==0) {
                    foreach ($total_variant['option_value_id1'] as $data => $variants) {
                            $variant_id=ProductVariant::insertGetId([
                                'product_id'        => $product_id,
                                'option_id'         => $total_variant['option_id1'][$data],
                                'option_value_id'   => $total_variant['option_value_id1'][$data],
                                'option_id2'        => isset($total_variant['option_id2'][$data])?$total_variant['option_id2'][$data]:'',
                                'option_value_id2'  => isset($total_variant['option_value_id2'][$data])?$total_variant['option_value_id2'][$data]:'',
                                'option_id3'        => isset($total_variant['option_id3'][$data])?$total_variant['option_id3'][$data]:'',
                                'option_value_id3'  => isset($total_variant['option_value_id3'][$data])?$total_variant['option_value_id3'][$data]:'',
                                'option_id4'        => isset($total_variant['option_id4'][$data])?$total_variant['option_id4'][$data]:'',
                                'option_value_id4'  => isset($total_variant['option_value_id4'][$data])?$total_variant['option_value_id4'][$data]:'',
                                'option_id5'        => isset($total_variant['option_id5'][$data])?$total_variant['option_id5'][$data]:'',
                                'option_value_id5'  => isset($total_variant['option_value_id5'][$data])?$total_variant['option_value_id5'][$data]:'',
                                'is_deleted'        => 0
                            ]); 
                        ProductVariantVendor::insert([
                            'product_id'            => $product_id,
                            'product_variant_id'    => $variant_id,
                            'base_price'            => $total_variant['base_price'][$data],
                            'retail_price'          => $total_variant['retail_price'][$data],
                            'minimum_selling_price' => $total_variant['minimum_price'][$data],
                            'display_variant'       => $total_variant['display'][$data],
                            'display_order'         => $total_variant['display_order'][$data],
                            'stock_quantity'        => $total_variant['stock_qty'][$data],
                            'sku'                   => $total_variant['sku'][$data],
                            'vendor_id'             => $vendor_id,
                        ]); 
                    }
                }
                else{
                    foreach ($total_variant['option_value_id1'] as $data => $variants) {

                        $variant_id=ProductVariant::where(['product_id'=> $product_id, 'option_id' => $total_variant['option_id1'][$data], 'option_value_id'   => $total_variant['option_value_id1'][$data], 'option_id2'=> isset($total_variant['option_id2'][$data])?$total_variant['option_id2'][$data]:'', 'option_value_id2'  => isset($total_variant['option_value_id2'][$data])?$total_variant['option_value_id2'][$data]:'', 'option_id3'=> isset($total_variant['option_id3'][$data])?$total_variant['option_id3'][$data]:'', 'option_value_id3'  => isset($total_variant['option_value_id3'][$data])?$total_variant['option_value_id3'][$data]:'', 'option_id4'=> isset($total_variant['option_id4'][$data])?$total_variant['option_id4'][$data]:'', 'option_value_id4'  => isset($total_variant['option_value_id4'][$data])?$total_variant['option_value_id4'][$data]:'', 'option_id5'=> isset($total_variant['option_id5'][$data])?$total_variant['option_id5'][$data]:'', 'option_value_id5'  => isset($total_variant['option_value_id5'][$data])?$total_variant['option_value_id5'][$data]:''])
                            ->first();

                            ProductVariantVendor::insert([
                                'product_id'            => $product_id,
                                'product_variant_id'    => $variant_id->id,
                                'base_price'            => $total_variant['base_price'][$data],
                                'retail_price'          => $total_variant['retail_price'][$data],
                                'minimum_selling_price' => $total_variant['minimum_price'][$data],
                                'display_variant'       => $total_variant['display'][$data],
                                'display_order'         => $total_variant['display_order'][$data],
                                'stock_quantity'        => $total_variant['stock_qty'][$data],
                                'vendor_id'             => $vendor_id,
                                'sku'                   => $total_variant['sku'][$data],
                            ]);   
                    }
                }
            $key++;
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $product = Product::where('id',$id)->first();
        if($product){
            $product->published  = 0;
            $product->is_deleted = 1;
            $product->deleted_at = date('Y-m-d H:i:s');
            $product->update();
        }

        $product_variant = ProductVariant::where('product_id',$id)->get();
        if($product_variant){
            foreach ($product_variant as $variant) {
                $variant = ProductVariant::find($variant->id);
                $variant->is_deleted  = 1;
                $variant->deleted_at = date('Y-m-d H:i:s');
                $variant->timestamps = false;
                $variant->update();    
            }            
        }

        $product_img = ProductImage::where('product_id',$id)->get();
        if($product_img){
            foreach ($product_img as $img) {
                $img = ProductImage::find($img->id);
                $img->is_deleted  = 1;
                $img->deleted_at = date('Y-m-d H:i:s');
                $img->timestamps = false;
                $img->update();
            }
        }
        
        if ($request->ajax()) return ['status'=>true];
        else return redirect()->route('products.index')->with('error','Product deleted successfully.!');
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
    
    public function generateRawOptions($options){
        $sql='';
        $totalOptions=count($options);
        if($totalOptions==1){
            $sql="Select * from (SELECT pov.option_id as optionID1,pov.option_value as optionValue1,pov.id as optionValueID1,pov.option_value_code as optionValueCode1 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".current($options).") a";
        }
        else if($totalOptions==2){
            list($option1,$option2)=$options;
            $sql ="Select * from (SELECT pov.option_id as optionID1,pov.option_value as optionValue1,pov.id as optionValueID1,pov.option_value_code as optionValueCode1 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option1.") a cross join (SELECT pov.option_id as optionID2,pov.option_value as optionValue2,pov.id as optionValueID2,pov.option_value_code as optionValueCode2 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option2.") b";
        }
        else if($totalOptions==3){
            list($option1,$option2,$option3)=$options;
            $sql ="Select * from (SELECT pov.option_id as optionID1,pov.option_value as optionValue1,pov.id as optionValueID1,pov.option_value_code as optionValueCode1 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option1.") a cross join (Select * from (SELECT pov.option_id as optionID2,pov.option_value as optionValue2,pov.id as optionValueID2,pov.option_value_code as optionValueCode2 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option2.") a cross join (SELECT pov.option_id as optionID3,pov.option_value as optionValue3,pov.id as optionValueID3,pov.option_value_code as optionValueCode3 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option3.") b)c";
        }
        else if($totalOptions==4){
            list($option1,$option2,$option3,$option4)=$options;
            $sql ="Select * from (SELECT pov.option_id as optionID1,pov.option_value as optionValue1,pov.id as optionValueID1,pov.option_value_code as optionValueCode1 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option1.") a cross join (Select * from (SELECT pov.option_id as optionID2,pov.option_value as optionValue2,pov.id as optionValueID2,pov.option_value_code as optionValueCode2 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option2.") a cross join (Select * from (SELECT pov.option_id as optionID3,pov.option_value as optionValue3,pov.id as optionValueID3, pov.option_value_code as optionValueCode3 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option3.") a cross join (SELECT pov.option_id as optionID4,pov.option_value as optionValue4,pov.id as optionValueID4,pov.option_value_code as optionValueCode4 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option4.") b)c) d";
        }
        else if($totalOptions==5){
            list($option1,$option2,$option3,$option4,$option5)=$options;
            $sql ="Select * from (SELECT pov.option_id as optionID1,pov.option_value as optionValue1,pov.id as optionValueID1,pov.option_value_code as optionValueCode1 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option1.") a cross join (Select * from (SELECT pov.option_id as optionID2,pov.option_value as optionValue2,pov.id as optionValueID2,pov.option_value_code as optionValueCode2 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option2.") a cross join (Select * from (SELECT pov.option_id as optionID3,pov.option_value as optionValue3,pov.id as optionValueID3,pov.option_value_code as optionValueCode3 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option3.") a cross join (Select * from (SELECT pov.option_id as optionID4,pov.option_value as optionValue4,pov.id as optionValueID4,pov.option_value_code as optionValueCode4 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option4.") a cross join (SELECT pov.option_id as optionID5,pov.option_value as optionValue5,pov.id as optionValueID5,pov.option_value_code as optionValueCode5 FROM `product_options` as po inner join `product_option_values` as pov on po.id= pov.option_id where po.id=".$option5.") b)c) d ) e";
        }
        return $sql;
    }

    public function productVariant(Request $request)
    {
        $options = json_decode($request->options,true);
        $vendors = json_decode($request->vendors,true);
        $product_code = $request->product_code;
        //dd($product_code);
        if ($request->has('existOption')) {
            $existOption = json_decode($request->existOption,true);
            $diff_options=array_diff($options, $existOption);
        }
            
        $removed_vendors_id = array();

        if ($request->has('existVendor')) {
            $existing_vendor=json_decode($request->existVendor,true);
            $diff_vendor=array_diff($vendors, $existing_vendor);
            $removed_vendors=array_merge(array_diff($existing_vendor,$vendors),array_diff($vendors, $existing_vendor));
            $removed_vendors_id = array_diff($removed_vendors,$vendors);

            if(array_diff($vendors, $existing_vendor)){
                $check_vendors = ProductVariantVendor::whereIn('vendor_id',$vendors)->exists();
                $vendors=$diff_vendor;

            }
        }
        $data['removed_vendors_id'] = $removed_vendors_id;

        //dd($options,$vendors);


        /*if($request->dataFrom=="edit"){
            if(count($diff_options)==0 && count($diff_vendor)==0){
                $check_vendors = ProductVariantVendor::whereIn('vendor_id',$vendors)->exists();
                if($check_vendors){
                    $vendors = [];
                }    
            }
        }*/

        $vendor_data = array();

        $data['data_from'] ="";
        if($request->dataFrom=="edit"){
            $data['data_from'] ="edit";
            $exist_options = json_decode($request->existOption,true);
            $exist_vendors = json_decode($request->existVendor,true);
        }
        $variant_options=[];
        $get_options = array();
        foreach ($vendors as $vendor) {
            $vendor_data[]=Vendor::find($vendor);
            $get_options = Option::whereIn('id',$options)->where('is_deleted',0)->get();
            $sql =$this->generateRawOptions($options);
            $variant_options[] = DB::select($sql);
        }

        // $data['product_code'] = str_replace(date('Y'),'00', $product_code);
         $data['product_code'] = $product_code;

        $data['vendors'] = $vendor_data;
        $data['option_values'] = $variant_options;
        $data['options']= $get_options;
        $data['option_count'] = count($options);

       if($request->dataFrom=="edit" && $request->deleteRequest==1){
            return view('admin.products.edit_variants',$data);
        }
        else{
            return view('admin.products.variants',$data);
        }
    }


    public function removeVariant(Request $request)
    {   
        if($request->id){
            $variant=ProductVariant::where('id',$request->id)->first();
            if($variant){
                $variant->is_deleted = 1;                
                $variant->deleted_at = date('Y-m-d H:i:s');
                $variant->timestamps= false;
                $variant->update();     
            }
            ProductVariantVendor::where('product_variant_id',$variant->id)
            ->update([
                'display_variant'=>0
            ]);
        }
    }

    public function removeImage(Request $request)
    {   
        if($request->id){
            $images=ProductImage::where('id',$request->id)->first();
            if($images){
                $images->is_deleted = 1;                
                $images->deleted_at = date('Y-m-d H:i:s');
                $images->timestamps= false;
                $images->update(); 
            }             
        }
                 
    }

    public function ProductImportController()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('import','read')) {
                abort(404);
            }
        }
        $data=array();
        $data['last_product_id']=Product::orderBy('id','DESC')->latest()->value('id');

        return view('admin.products.product_import',$data);
    }

    public function ProductImportPostController(Request $request)
    {

        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('import','create')) {
                abort(404);
            }
        }

        $this->validate(request(),[
            'product_sheet' => 'required'
        ]);

        try {
             Excel::import(new ProductImport, $request->file('product_sheet')); 
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors=[];
            foreach ($e->failures() as $failure) {
                $errors[] = "Error(s) in column " . $failure->attribute() . " at row " . $failure->row() . " with the message : <strong>" . implode($failure->errors()) ."</strong>";
            }
            return redirect()->back()->withErrors($errors);

        }
      return Redirect::back()->with('success','Products details imported successfully...!');
    }

    public function DownloadSampleImportSheet()
    {
        $attachment="PacificMediHub Import Sheet.xlsx";
        $path=public_path('theme/sample_datas/').$attachment;
        return Response::download($path, $attachment);
    }
    public function AddBrandAjax(Request $request)
    {
        $this->validate(request(), 
            ['brand_name' => 'required', 'manf_name' => 'required'],
            [
                'brand_name.required'   => 'Brand name is required'   ,
                'manf_name.required'    => 'Manufacturing name is required',
            ]
        ); 

        if($request->brand_published){$published = 1;}else{$published = 0;}
        $image= $request->hasFile('brand_image');
        if($image){
            $photo          = $request->file('brand_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('brand_image')->getClientOriginalExtension();
            $image_name     = strtotime("now").".".$file_extension;
            $request->brand_image->move(public_path('theme/images/brands/'), $image_name);
        }
        else{
            $image_name = NULL;
        }
        $add = new Brand;
        $add->name   = $request->brand_name;
        $add->manf_name = $request->manf_name;
        $add->manf_country_id = $request->country_id;
        $add->image  = $image_name;
        $add->published = $published;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();

        if ($published==1) {
             return ['brand_name'=>$request->brand_name,'brand_id'=>$add->id] ;
        }
        else{
            return null;
        }
    }
    public function AddCategoryAjax(Request $request)
    {
        $this->validate(request(), 
            [
                'category_name'  => 'required',
                'search_engine'  => 'required'
            ],
            [
                'category_name.required'   => 'Category name is required'   ,
                'search_engine.required'    => 'Search engine name is required',
            ]
        );

        if($request->category_published){$published = 1;}else{$published = 0;}
        if($request->category_homepage){$homepage = 1;}else{$homepage = 0;}

        
        
        if($request->display_order!=NULL){
            $display_order = $request->display_order;
        }else{
            $display_order = Categories::where('is_deleted',0)->orderBy('id', 'desc')->take(1)->value('display_order');
            $display_order = $display_order+1;
        }

        $image= $request->hasFile('category_image');
        if($image){
            $photo          = $request->file('category_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('category_image')->getClientOriginalExtension();
            $image_name     = 'img_'.strtotime("now").".".$file_extension;
            $request->category_image->move(public_path('theme/images/categories/'), $image_name);
        }
        else{
            $image_name = NULL;
        }

        $icon= $request->hasFile('category_icon');
        if($icon){
            $photo          = $request->file('category_icon');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('category_icon')->getClientOriginalExtension();
            $icon_name      = 'icn_'.strtotime("now").".".$file_extension;
            $request->category_icon->move(public_path('theme/images/categories/icons/'), $icon_name);
        }
        else{
            $icon_name = NULL;
        }

        $add = new Categories;
        $add->parent_category_id = $request->parent_category;
        $add->name   = $request->category_name;
        $add->image  = $image_name;
        $add->icon   = $icon_name;
        $add->description = $request->category_description;
        $add->published = $published;
        $add->show_home = $homepage;
        $add->display_order = $display_order;
        $add->search_engine_name = $request->search_engine;
        $add->meta_title = $request->meta_title;
        $add->meta_keyword = $request->meta_keyword;
        $add->meta_description = $request->meta_description;
        $add->created_at = date('Y-m-d H:i:s');
        $add->save();

        if ($published==1) {
             return ['category_name'=>$request->category_name,'category_id'=>$add->id] ;
        }
        else{
            return null;
        }
       
    }
}
