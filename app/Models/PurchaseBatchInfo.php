<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseBatchInfo extends Model
{
    protected $table="purchase_batch_info";
    protected $fillable=['purchase_id','product_id','product_variant_id','batch_id','expiry_date'];
    public $timestamps=false;
}
