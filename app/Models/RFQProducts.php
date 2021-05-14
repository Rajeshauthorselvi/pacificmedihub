<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class RFQProducts extends Model
{
    protected $table="rfq_products";
    public $timestamps=false;
    
    protected $fillable=[
        'rfq_id',
        'product_id',
        'product_variant_id',
        'base_price',
        'retail_price',
        'minimum_selling_price',
        'quantity',
        'last_rfq_price',
        'rfq_price',
        'discount_value',
        'discount_type',
        'final_price',
        'total_price',
        'sub_total'
    ];

    static function VariationPrice($product_id,$product_variation_id,$rfq_id)
    {

		$variation_price=self::where('product_variant_id',$product_variation_id)
						 ->where('product_id',$product_id)
						 ->where('rfq_id',$rfq_id)
						 ->first();
		return $variation_price;
    }

    static function allAmount($rfq_id)
    {
        $get_all_amount = self::where('rfq_id',$rfq_id)->get();
        $total_amount=0;
        $total_qty=0;
        foreach ($get_all_amount as $get_amount) {
            $total_amount += $get_amount->final_price*$get_amount->quantity;
            $total_qty += $get_amount->quantity;
        }
        $data['total_amount'] = $total_amount;
        $data['total_qty']    = $total_qty;
        $all_amount = $data;
    	return $all_amount;
    }

    static function totalAmount($rfq_id,$product_id=0){
        $get_total=self::where('rfq_id',$rfq_id)->where('product_id',$product_id)->first();
        $data['amount'] = self::where('rfq_id',$rfq_id)->where('product_id',$product_id)->sum('sub_total');
        $data['qty']    = self::where('rfq_id',$rfq_id)->where('product_id',$product_id)->sum('quantity');
        $total_amount   = $data;
        return $total_amount;
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'product_variant_id');
    }
    
    public function variantvendor()
    {
        return $this->belongsTo(ProductVariantVendor::class,'product_variant_id');
    }
    
}
