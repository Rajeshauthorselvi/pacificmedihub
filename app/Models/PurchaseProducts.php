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
}
