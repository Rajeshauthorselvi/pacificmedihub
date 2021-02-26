<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wastage;
use App\Models\WastageProducts;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Vendor;
use App\Models\OptionValue;
use Illuminate\Http\Request;
use Redirect;
use Response;
use Auth;
use DB;
class WastageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $wastages=Wastage::orderBy('created_at','desc')->get();
        
        $w_stage=array();
        foreach ($wastages as $key => $wastage) {

           $user_role=DB::table('roles')->where('id',$wastage->created_by)->value('name');

            $w_stage[]=[
                'id'    => $wastage->id,
                'date'  => date('d-m-Y',strtotime($wastage->created_at)),
                'reference_number'  => $wastage->reference_number,
                'notes' => $wastage->notes,
                'created_by'    => $user_role
            ];
        }

        $data['wastages']=$w_stage;
        return view('admin.stock.wastage.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=array();
        $wastage_count=Wastage::count();
        $data['ref_data']='WTG-'.date('Y').'-'.($wastage_count+1);

        return view('admin.stock.wastage.create',$data);
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
            'variant'   => 'required'
        ],['variant.required'=>'Product is required']);
        $variant=$request->get('variant');
        $wastage=[
            'reference_number'  =>$request->reference_number,
            'notes'  => $request->note,
            'created_by'  =>Auth::user()->role_id,
            'created_at'  => date('Y-m-d H:i:s')
        ];
       $wastage_id=Wastage::insertGetId($wastage);
       $product_id=$variant['product_id'];
       $variant_id=$variant['variant_id'];
        foreach ($variant['stock_qty'] as $key => $stock_quantity) {
            if ($stock_quantity>0) {
                $product_id=$product_id[$key];
                $variant_id=$variant_id[$key];

                $watage_products=WastageProducts::insert([
                    'wastage_id'    => $wastage_id,
                    'product_id'    => $product_id,
                    'product_variation_id'    => $variant_id,
                    'quantity'      => $stock_quantity,
                    'created_at'    => date('Y-m-d H:i:s')
                ]);
                $avalible_quantity=ProductVariantVendor::where('product_variant_id',$variant_id)
                                ->where('product_id',$product_id)
                                ->where('display_variant',1)
                                ->value('stock_quantity');
                $total_quantity=$avalible_quantity-$stock_quantity;
                ProductVariantVendor::where('product_variant_id',$variant_id)
                ->where('display_variant',1)
                ->where('product_id',$product_id)
                ->update(['stock_quantity'=>$total_quantity]);
            }
        }

        return Redirect::route('wastage.index')->with('success','Wastage added successfully.!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function show(Wastage $wastage)
    {
        $data=$product_data=array();
        $data['wastages']=Wastage::where('id',$wastage->id)->first();
        $wastage_products=WastageProducts::where('wastage_id',$wastage->id)->groupBy('product_id')->get();
        $data['wastage_quantity']=WastageProducts::where('wastage_id',$wastage->id)
                          ->pluck('quantity','product_variation_id')
                          ->toArray();

        foreach ($wastage_products as $key => $product) {

            $product_name    = Product::where('id',$product->product_id)->value('name');
            $options         = $this->Options($product->product_id);

            $all_variants    = WastageProducts::where('wastage_id',$wastage->id)
                               ->where('product_id',$product->product_id)
                               ->pluck('product_variation_id')
                               ->toArray();
            $product_variant = $this->Variants($product->product_id,$all_variants);
            $product_data[$product->product_id] = [
              'row_id'          => $product->id,
              'purchase_id'     => $wastage->id,
              'product_id'      => $product->product_id,
              'product_name'    => $product_name,
              'options'         => $options['options'],
              'option_count'    => $options['option_count'],
              'product_variant' => $product_variant
            ];
        }
        $data['wastage_products']=$product_data;
        return view('admin.stock.wastage.view',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function edit(Wastage $wastage)
    {
        $data=array();
         $data['wastages']=$wastages=Wastage::where('id',$wastage->id)->first();

         $wastage_products=WastageProducts::where('wastage_id',$wastage->id)
                           ->get();
        $options=$this->Options($wastages->product_id);

        
        $data['options'] = $options['options'];
        $pro_wastage=array();
        foreach ($wastage_products as $key => $products) {
            $product_name=Product::where('id',$products->product_id)->value('name');
            $variant=ProductVariant::where('id',$products->product_variation_id)->where('disabled',0)->where('is_deleted',0)->first();
            $pro_wastage[]=[
                'id'  => $products->id,
                'product_id'  => $products->product_id,
                'product_name'  => $product_name,
                'option_value_id1'  => $this->OptionValues($variant->option_value_id),
                'option_value_id2'  => $this->OptionValues($variant->option_value_id2),
                'option_value_id3'  => $this->OptionValues($variant->option_value_id3),
                'option_value_id4'  => $this->OptionValues($variant->option_value_id4),
                'option_value_id5'  => $this->OptionValues($variant->option_value_id5),
                'quantity'=>$products->quantity,
            ];
        }
        $data['wastage_products']=$pro_wastage;

        return view('admin.stock.wastage.edit',$data);
    }
    public function OptionValues($value_id='')
    {
        $option_value=OptionValue::where('id',$value_id)->value('option_value');

        return $option_value;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wastage $wastage)
    {
        $quantitys=$request->quantity;

            Wastage::where('id',$wastage->id)
            ->update([
                'reference_number'  => $request->reference_number,
                'notes' =>  $request->note
            ]);

        foreach ($quantitys as $key => $quantity) {
            WastageProducts::where('id',$key)
            ->update([
                'quantity'=>$quantity
            ]);
        }
        
        return Redirect::route('wastage.index')->with('success','Wastage modified successfully.!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Wastage $wastage)
    {
        $delete_wastage = Wastage::where('id',$wastage->id)->delete();
        if($delete_wastage){
            WastageProducts::where('wastage_id',$wastage->id)->delete();    
        }
        
        if ($request->ajax())  return ['status'=>true];
        else return Redirect::route('wastage.index')->with('success','Wastage deleted successfully.!');
 
    }

    public function ProductSearch(Request $request)
    {

        $search_type=$request->product_search_type;
        $product_id=$request->product_id;
        $data=$options=array();
            
        if ($search_type=="options") {
            $options=$this->Options($product_id);
            $data['product_variant']=$this->Variants($product_id);

             $data['vendors'] = Vendor::where('is_deleted',0)->orderBy('name','asc')->get();
            $data['options'] = $options['options'];
            $data['options_json'] = Response::json($options['options']);

            $data['option_count'] = $options['option_count'];
            $data['product_id'] = $product_id;
            $data['product_name']=Product::where('id',$product_id)->value('name');

           $view=view('admin.stock.wastage.variations',$data)->render();

           return $view;
        }
        elseif ($search_type=="header") {
            $data['product_id'] = $product_id;
            $data['product_variant']=$this->Variants($product_id);
            $options=$this->Options($product_id);
           $data['options'] = $options['options'];
            $data['options_json'] = Response::json($options['options']);

            $data['option_count'] = $options['option_count'];

           // $data['view']=view('admin.purchase.variations',$data)->render();
           return Response::json($data);
        }
        elseif ($search_type=="product") {

            $product_names=Product::where("name","LIKE","%".$request->input('name')."%")
                            ->where('is_deleted',0)
                            ->where('published',1)
                            ->pluck('name','id')
                            ->toArray();
            $names=array();
            foreach ($product_names as $key => $name) {
                $names[]=[
                    'value'=>$key,
                    'label'  => $name
                ];
            }  
        }
        return response()->json($names);
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
            $product_variants[$key]['stock_quantity']=$variant_details->stock_quantity;


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
}
