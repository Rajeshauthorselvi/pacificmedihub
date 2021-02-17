<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchaseProducts;
use App\User;
class Purchase extends Model
{
   protected $table="purchase";
   public $timestamps=false;

   public function purchaseorder()
   {
   		return $this->hasMany(PurchaseProducts::class,'purchase_id','id');
   }

    public function getVendor()
    {
    	return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function statusName()
    {
    	return $this->belongsTo(OrderStatus::class,'purchase_status');
    }

    public function oderTax()
    {
        return $this->belongsTo(Tax::class,'order_tax');
    }

    public function payTerm()
    {
        return $this->belongsTo(PaymentTerm::class,'payment_term');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}	
