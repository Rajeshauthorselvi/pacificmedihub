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
        $this->validate(request(), [
            'product_name' => 'required',
            'product_code' => 'required',
            'category'     => 'required'
        ]);

        if($request->published){$published = 1;}else{$published = 0;}
        if($request->homepage){$homepage = 1;}else{$homepage = 0;}

        $image= $request->hasFile('main_image');
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

                $i = 0;
                foreach ($request->variant['dosage'] as $dosage) {
                    $variant_data[$i]['dosage'] = $dosage;
                    $i = $i + 1;
                }
                $i = 0;
                foreach ($request->variant['package'] as $package) {
                    $variant_data[$i]['package'] = $package;
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
                foreach ($request->variant['vendor'] as $vendor) {
                    $variant_data[$i]['vendor'] = $vendor;
                    $i = $i + 1;
                }

                $i = 0;
                foreach ($request->variant['default'] as $default) {
                    $variant_data[$i]['default'] = $default;
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
                    $add->dosage = $value['dosage'];
                    $add->package = $value['package'];
                    $add->base_price = $value['base_price'];
                    $add->retail_price = $value['retail_price'];
                    $add->minimum_selling_price = $value['minimum_price'];
                    $add->default_variant = $value['default'];
                    $add->display_variant = $value['display'];
                    $add->display_order = $value['display_order'];
                    $add->stock_quantity = $value['stock_qty'];
                    $add->timestamps = false;
                    $add->save();

                    foreach($value['vendor'] as $id) {
                        $vendor = new ProductVariantVendor();
                        $vendor->product_id = $product->id;
                        $vendor->product_variant_id = $add->id;
                        $vendor->variant_id = isset($id)?$id:NULL;
                        $vendor->timestamps = false;
                        $vendor->save();
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
        $data['product_variant'] = ProductVariant::where('product_id',$id)->get();
        $data['product_image'] = ProductImage::where('product_id',$id)->get();
        $data['categories'] = Categories::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['brands'] = Brand::where('is_deleted',0)->orderBy('name','asc')->get();
        $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('name','asc')->get();
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
        dd($request->all());
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

    public function productVariant(Request $request)
    {
        $options = json_decode($request->options,true);
        $vendors = json_decode($request->vendors,true);


        $data=array();
        $get_options = Option::whereIn('id',$options)->pluck('option_name','id')->toArray();
        $data['get_option'] = $get_options;


        $get_option_values = OptionValue::whereIn('option_id',$options)->get();
        $option_values = array();
        foreach ($get_option_values as $key => $option) {
            $option_values[]=[
                'option_id'=>$option->option_id,
                'option_value_id'=>$option->id,
                'option_value'=>$option->option_value
            ];
        }
      /*  echo "<pre>";
        print_r($option_values);*/

        $data['option_values']=$option_values;
        return view('admin/products/variants',$data);
    }
}
