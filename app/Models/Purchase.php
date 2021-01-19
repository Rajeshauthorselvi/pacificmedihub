<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseProducts;
class Purchase extends Model
{
   protected $table="purchase";
   public $timestamps=false;

   public function purchaseorder()
   {
   		return $this->hasMany(PurchaseProducts::class,'purchase_id','id');
   }
}	
