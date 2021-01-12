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
use App\Models\Option;
use App\Models\OptionValue;
use Redirect;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $get_products = Product::where('is_deleted',0)->orderBy('created_at','desc')->get();

        $products = array();

        foreach($get_products as $key => $product){
            $variant = ProductVariant::where('product_id',$product->id)->where('is_deleted',0)->where('display_order',1)->first();
            $products[$key]['id'] = $product->id;
            $products[$key]['image'] = $product->main_image;
            $products[$key]['name'] = $product->name;
            $products[$key]['code'] = $product->code;
            $products[$key]['sku'] = $product->sku;
            $products[$key]['category'] = $product->category->name;
            $products[$key]['stock'] = isset($variant->stock_quantity)?$variant->stock_quantity:'-';
            $products[$key]['base_price'] = isset($variant->base_price)?$variant->base_price:'-';
            $products[$key]['retail_price'] = isset($variant->retail_price)?$variant->retail_price:'-';
            $products[$key]['minimum_price'] = isset($variant->minimum_selling_price)?$variant->minimum_selling_price:'-';
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
    public function create()
    {
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['brands'] = Brand::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['product_options'] = Option::where('published',1)->where('is_deleted',0)->orderBy('display_order','asc')->get();
        //dd($data);
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
        //dd($request->all());
        $this->validate(request(), [
            'product_name' => 'required',
            'product_code' => 'required',
            'category'     => 'required'
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
        $product->treatment_information = $request->treatment_information;
        $product->dosage_instructions = $request->dosage_instructions;
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

        if($product->id){
            if($request->variant){
                $variant_data = [];

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
                
                foreach ($variant_data as $value) {
                    $add = new ProductVariant();
                    $add->product_id = $product->id;
                    $add->product_option_id1 = $value['option_id1'];
                    $add->product_option_value_id1 = $value['option_value_id1'];
                    $add->product_option_id2 = $value['option_id2'];
                    $add->product_option_value_id2 = $value['option_value_id2'];
                    $add->product_option_id3 = $value['option_id3'];
                    $add->product_option_value_id3 = $value['option_value_id3'];
                    $add->base_price = $value['base_price'];
                    $add->retail_price = $value['retail_price'];
                    $add->minimum_selling_price = $value['minimum_price'];
                    $add->display_variant = $value['display'];
                    $add->display_order = $value['display_order'];
                    $add->stock_quantity = $value['stock_qty'];
                    $add->vendor_id = $value['vendor_id'];
                    $add->timestamps = false;
                    $add->save();

                    /*foreach($value['vendor'] as $id) {
                        $vendor = new ProductVariantVendor();
                        $vendor->product_id = $product->id;
                        $vendor->product_variant_id = $add->id;
                        $vendor->variant_id = isset($id)?$id:NULL;
                        $vendor->timestamps = false;
                        $vendor->save();
                    }*/
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
            return redirect()->route('product.index')->with('success','New Product Added successfully...!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!'); 
        }
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
        $data['product'] = Product::find($id);
        $variant = ProductVariant::where('product_id',$id)->where('is_deleted',0)->first();
        $options = array();
        
        if(($variant->product_option_id1!=NULL)&&($variant->product_option_id2==NULL)&&($variant->product_option_id3==NULL)){
            $options[] = $variant->optionName1->option_name;
            $option_count = 1;
        }elseif(($variant->product_option_id1!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3==NULL)){
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $option_count = 2;
        }elseif(($variant->product_option_id1!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)){
            $options[] = $variant->optionName1->option_name;
            $options[] = $variant->optionName2->option_name;
            $options[] = $variant->optionName3->option_name;
            $option_count = 3;
        }
        $productVariants = ProductVariant::where('product_id',$id)->where('is_deleted',0)->get();
        $product_variants = array();
        foreach ($productVariants as $key => $variants) {
            $product_variants[$key]['variant_id'] = $variants->id;
            if(($variant->product_option_id1!=NULL)&&($variant->product_option_id2==NULL)&&($variant->product_option_id3==NULL)){
                $product_variants[$key]['option_id1'] = $variants->product_option_id1;
                $product_variants[$key]['option_value_id1'] = $variants->product_option_value_id1;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
            }elseif(($variant->product_option_id1!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3==NULL)){
                $product_variants[$key]['option_id1'] = $variants->product_option_id1;
                $product_variants[$key]['option_value_id1'] = $variants->product_option_value_id1;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
                $product_variants[$key]['option_id2'] = $variants->product_option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->product_option_value_id2;
                $product_variants[$key]['option_value2'] = $variants->optionValue2->option_value;
            }elseif(($variant->product_option_id1!=NULL)&&($variant->product_option_id2!=NULL)&&($variant->product_option_id3!=NULL)){
                $product_variants[$key]['option_id1'] = $variants->product_option_id1;
                $product_variants[$key]['option_value_id1'] = $variants->product_option_value_id1;
                $product_variants[$key]['option_value1'] = $variants->optionValue1->option_value;
                $product_variants[$key]['option_id2'] = $variants->product_option_id2;
                $product_variants[$key]['option_value_id2'] = $variants->product_option_value_id2;
                $product_variants[$key]['option_value2'] = $variants->optionValue2->option_value;
                $product_variants[$key]['option_id3'] = $variants->product_option_id3;
                $product_variants[$key]['option_value_id3'] = $variants->product_option_value_id3;
                $product_variants[$key]['option_value3'] = $variants->optionValue3->option_value;
            }
            $product_variants[$key]['base_price'] = $variants->base_price;
            $product_variants[$key]['retail_price'] = $variants->retail_price;
            $product_variants[$key]['minimum_selling_price'] = $variants->minimum_selling_price;
            $product_variants[$key]['display_order'] = $variants->display_order;
            $product_variants[$key]['stock_quantity'] = $variants->stock_quantity;
            $product_variants[$key]['display_variant'] = $variants->display_variant;
            $product_variants[$key]['vendor_id'] = $variants->vendor_id;
            $product_variants[$key]['vendor_name'] = $variants->vendorName->name;
        }
        $data['option_count'] = $option_count;
        $data['get_options'] = $options;
        $data['product_variant'] = $product_variants;
        $data['product_images'] = ProductImage::where('product_id',$id)->where('is_deleted',0)->get();
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['brands'] = Brand::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['product_options'] = Option::where('published',1)->where('is_deleted',0)->orderBy('display_order','asc')->get();
        //dd($data);
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
        //dd($request->all());
         $this->validate(request(), [
            'product_name' => 'required',
            'product_code' => 'required',
            'category'     => 'required'
        ]);

        if($request->published){$published = 1;}else{$published = 0;}
        if($request->homepage){$homepage = 1;}else{$homepage = 0;}

        $product = Product::where('id',$id)->first();

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
        $product->sku = $request->product_sku;
        $product->category_id = $request->category;
        $product->brand_id = $request->brand;
        $product->main_image = $image_name;
        $product->short_description = $request->short_description;
        $product->long_description = $request->product_details;
        $product->treatment_information = $request->treatment_information;
        $product->dosage_instructions = $request->dosage_instructions;
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

        if($product->id){
            if($request->variant){
                $variant_data = [];

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
                
                foreach ($variant_data as $value) {
                    if(isset($value['id'])){
                        $add = ProductVariant::find($value['id']);
                        $add->product_id = $product->id;
                        $add->product_option_id1 = $value['option_id1'];
                        $add->product_option_value_id1 = $value['option_value_id1'];
                        $add->product_option_id2 = $value['option_id2'];
                        $add->product_option_value_id2 = $value['option_value_id2'];
                        $add->product_option_id3 = $value['option_id3'];
                        $add->product_option_value_id3 = $value['option_value_id3'];
                        $add->base_price = $value['base_price'];
                        $add->retail_price = $value['retail_price'];
                        $add->minimum_selling_price = $value['minimum_price'];
                        $add->display_variant = $value['display'];
                        $add->display_order = $value['display_order'];
                        $add->stock_quantity = $value['stock_qty'];
                        $add->vendor_id = $value['vendor_id'];
                        $add->timestamps = false;
                         $add->update();
                    }else{
                        $add = new ProductVariant();
                        $add->product_id = $product->id;
                        $add->product_option_id1 = $value['option_id1'];
                        $add->product_option_value_id1 = $value['option_value_id1'];
                        $add->product_option_id2 = $value['option_id2'];
                        $add->product_option_value_id2 = $value['option_value_id2'];
                        $add->product_option_id3 = $value['option_id3'];
                        $add->product_option_value_id3 = $value['option_value_id3'];
                        $add->base_price = $value['base_price'];
                        $add->retail_price = $value['retail_price'];
                        $add->minimum_selling_price = $value['minimum_price'];
                        $add->display_variant = $value['display'];
                        $add->display_order = $value['display_order'];
                        $add->stock_quantity = $value['stock_qty'];
                        $add->vendor_id = $value['vendor_id'];
                        $add->timestamps = false;
                        $add->save();
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
            return redirect()->route('product.index')->with('success','New Product Added successfully...!');
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!'); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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

        return redirect()->route('product.index')->with('error','Product deleted successfully.!');
    }

    public function productVariant(Request $request)
    {
        $options = json_decode($request->options,true);
        $vendors = json_decode($request->vendors,true);

        $data=array();
        $get_options = Option::whereIn('id',$options)->pluck('option_name','id')->toArray();
        $data['get_option'] = $get_options;

        $get_option_values = OptionValue::whereIn('option_id',$options)->get();
        $option_values = array();
        $get_vendor = array();

        $option_count = count($options);
        $data['option_count'] = $option_count;
        foreach ($vendors as $vendor) {
            $get_vendor[] = Vendor::find($vendor);
            if($option_count==1){
                $option_values[] = DB::select('select id as opt_val_id1, option_id as opt_id1, option_value as opt_val1 from product_option_values where option_id = "'.$options[0].'"');
            }
            elseif($option_count==2){
                $option_values[] = DB::select('select v1.id as opt_val_id1, v2.id as opt_val_id2, v1.option_id as opt_id1, v1.option_value as opt_val1, v2.option_id as opt_id2, v2.option_value as opt_val2 from (select * from product_option_values where option_id = "'.$options[0].'") v1 inner join (select * from product_option_values where option_id = "'.$options[1].'") v2 on v1.option_id <> v2.option_id and v1.option_id = "'.$options[0].'"');
            }else if($option_count==3){
                $option_values[] = DB::select('select v1.id as opt_val_id1, v2.id as opt_val_id2, v3.id as opt_val_id3, v1.option_id as opt_id1, v1.option_value as opt_val1, v2.option_id as opt_id2, v2.option_value as opt_val2, v3.option_id as opt_id3, v3.option_value as opt_val3 from (select * from product_option_values where option_id = "'.$options[0].'") v1 inner join (select * from product_option_values where option_id = "'.$options[1].'") v2 on v1.option_id <> v2.option_id and v1.option_id = "'.$options[0].'" inner join (select * from product_option_values where option_id = "'.$options[2].'") v3 on v1.option_id <> v3.option_id');
            }            
        }
        $data['vendors'] = $get_vendor;
        $data['option_values'] = $option_values;
        return view('admin/products/variants',$data);
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
}
