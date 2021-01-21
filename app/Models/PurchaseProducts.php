<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OptionValue;
class PurchaseProducts extends Model
{
    protected $table="purchase_products";
    public $timestamps=false;

     public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id');
    }
     public function optionvalue()
    {
        return $this->belongsTo(OptionValue::class,'option_value_id');
    }

    static function StockQuantity($product_id,$type='')
    {

       
      $total= self::where('product_id',$product_id);
            if ($type=="sub_total") {
                 $total= $total->pluck('sub_total')->toArray();
            }
            else{
                 $total= $total->pluck('quantity')->toArray();
            }
            
        return $total;
    }
}
