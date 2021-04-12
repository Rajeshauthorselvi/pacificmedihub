<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class OrderProducts extends Model
{
	protected $table="order_products";
    public $timestamps=false;

    static function OrderTotalProduct($order_id='')
    {
    	return self::where('order_id',$order_id)->sum('sub_total');
    }

    static function VariationPrice($product_id,$product_variation_id,$order_id)
    {

		$variation_price=self::where('product_variation_id',$product_variation_id)
						 ->where('product_id',$product_id)
						 ->where('order_id',$order_id)
						 ->first();
		return $variation_price;
    }

    static function TotalDatas($order_id,$product_id=0)
    {
    	$total_datas=self::select(DB::raw('sum(quantity) as quantity'),DB::raw('sum(sub_total) as sub_total'),DB::raw('sum(final_price) as final_price'),'batch_ids');

    		if ($product_id!=0) {
    			$total_datas=$total_datas->where('product_id',$product_id);
    		}
    		$total_datas=$total_datas->where('order_id',$order_id)->first();
    		return $total_datas;
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class,'product_variation_id');
    }
    static function BatchInfos($ids)
    {
        $batch_exp=DB::table('purchase_batch_info')->whereIn('id',$ids)->pluck('expiry_date')->toArray();
        $batch_exp=array_filter($batch_exp);
        return ['batch_exp'=>implode(',', $batch_exp)];
    }
}
