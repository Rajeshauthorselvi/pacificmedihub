<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class RFQProducts extends Model
{
    protected $table="rfq_products";
    public $timestamps=false;
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
            $total_amount += $get_amount->retail_price*$get_amount->quantity;
            $total_qty += $get_amount->quantity;
        }
        $data['total_amount'] = $total_amount;
        $data['total_qty']    = $total_qty;
        $all_amount = $data;
    	return $all_amount;
    }

    static function totalAmount($rfq_id,$product_id=0){
        $get_total=self::where('rfq_id',$rfq_id)->where('product_id',$product_id)->first();
        $data['amount'] = $get_total->retail_price*$get_total->quantity;
        $data['qty']    = $get_total->quantity;
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
    
}
