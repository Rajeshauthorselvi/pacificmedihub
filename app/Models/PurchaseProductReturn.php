<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseProducts;
class PurchaseProductReturn extends Model
{
    protected $table="purchase_return_products";
    public $timestamps	=false;


    static function ReturnPrice($product_id,$variant_id,$purchase_return_id)
    {
    	var_dump($variant_id);
    	$data=self::where('product_id',$product_id)
    	->where('purchase_variation_id',$variant_id)
    	->first();

    	
    }
}
