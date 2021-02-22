<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
class PurchaseStockHistory extends Model
{
    protected $table="purchase_stock_history";

    public function purchasedetails()
    {
    	return $this->belongsTo(Purchase:: class,'purchase_id');
    }
    public function price()
    {
    	return $this->belongsTo(PurchaseProducts:: class,'purchase_product_id');
    }
}
