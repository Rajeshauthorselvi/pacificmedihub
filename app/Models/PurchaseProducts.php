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

    static function StockQuantity($product_id,$option_id1='', $option_id2='', $option_id3='', $option_id4='', $option_value_id1='', $option_value_id2='', $option_value_id3='', $option_value_id4='', $option_value_id5,$option_count,$type='',$purchase_id)
    {

    $query=self::where('product_id',$product_id)->where('purchase_id',$purchase_id);
      if ($option_count==1) {
          $query->where('option_id',$option_id1)->where('option_value_id',$option_value_id1);
      }
      if ($option_count==2) {
          $query->where('option_id',$option_id1)
          ->where('option_value_id',$option_value_id1)
          ->where('option_id2',$option_id2)
          ->where('option_value_id2',$option_value_id2);
      }
      if ($option_count==3) {
          $query->where('option_id',$option_id1)
          ->where('option_value_id',$option_value_id1)
          ->where('option_id2',$option_id2)
          ->where('option_value_id2',$option_value_id2)
          ->where('option_id3',$option_id3)
          ->where('option_value_id3',$option_value_id3);
      }
      if ($option_count==4) {
          $query->where('option_id',$option_id1)
          ->where('option_value_id',$option_value_id1)
          ->where('option_id',$option_id2)
          ->where('option_value_id',$option_value_id2)
          ->where('option_id3',$option_id3)
          ->where('option_value_id3',$option_value_id3)
          ->where('option_id4',$option_i4)
          ->where('option_value_id4',$option_value_id4);
      }
      if ($option_count==5) {
              $query->where('option_id',$option_id1)
              ->where('option_value_id',$option_value_id1)
              ->where('option_id',$option_id2)
              ->where('option_value_id',$option_value_id2)
              ->where('option_id',$option_id3)
              ->where('option_value_id',$option_value_id3)
              ->where('option_id4',$option_i4)
              ->where('option_value_id4',$option_value_id4)
              ->where('option_id5',$option_i5)
              ->where('option_value_id5',$option_value_id5);
      }


      if ($type=="sub_total") {
          $total=$query->value('sub_total');
      }
      else {
          $total=$query->value('quantity');
      }

            
        return $total;
    }
}
