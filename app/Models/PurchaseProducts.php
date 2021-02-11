<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OptionValue;
use DB;
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
    static function VariationPrice($product_id,$product_variation_id,$purchase_id)
    {
        $variation_price=self::where('product_variation_id',$product_variation_id)
                 ->where('product_id',$product_id)
                 ->where('purchase_id',$purchase_id)
                 ->first();
        return $variation_price;
    }
    static function ProductPrice($product_id,$variant_id)
    {
      $product_price=DB::table('product_variant_vendors')->where('product_id',$product_id)->where('product_variant_id',$variant_id)->value('base_price');

      return $product_price;
    }
    static function TotalDatas($purchase_id,$product_id=0)
    {
      $total_datas=self::select(DB::raw('sum(quantity) as quantity'),DB::raw('sum(sub_total) as sub_total'));

        if ($product_id!=0) {
          $total_datas=$total_datas->where('product_id',$product_id);
        }
        $total_datas=$total_datas->where('purchase_id',$purchase_id)->first();
        return $total_datas;
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
