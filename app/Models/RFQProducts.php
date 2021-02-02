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

    static function TotalDatas($rfq_id,$product_id=0)
    {
    	$total_datas=self::select(DB::raw('sum(quantity) as quantity'),DB::raw('sum(sub_total) as sub_total'),DB::raw('sum(rfq_price) as rfq_price'));

    		if ($product_id!=0) {
    			$total_datas=$total_datas->where('product_id',$product_id);
    		}
    		$total_datas=$total_datas->where('rfq_id',$rfq_id)->first();
    		return $total_datas;
    }
}
