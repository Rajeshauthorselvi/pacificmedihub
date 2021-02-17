<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    protected $table="purchase_return";
    protected $fillable=['purchase_or_order_id','purchase_or_order_id','customer_or_vendor_id','order_type','payment_status','return_status','return_notes','staff_notes','created_at','updated_at'];
}
