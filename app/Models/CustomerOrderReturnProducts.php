<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerOrderReturnProducts extends Model
{
    protected $table="customer_order_return_products";

    static function qtyData($product_id,$product_variation_id,$order_id)
    {

		$variation_price=self::where('order_product_variant_id',$product_variation_id)
						 ->where('order_product_id',$product_id)
						 ->where('order_id',$order_id)
						 ->first();
		return $variation_price;
    }

    static function ReturnProducts($order_id,$product_id,$variant_id)
    {
    	$products=self::where('order_id',$order_id)
    			  ->where('order_product_id',$product_id)
    			  ->where('order_product_variant_id',$variant_id)
    			  ->first();

    	return $products;
    }
}
