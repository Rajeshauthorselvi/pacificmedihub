<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CustomerOrderReturn extends Model
{
    protected $table="customer_order_return";

    public function statusName()
    {
    	return $this->belongsTo(OrderStatus::class,'order_return_status');
    }

    public function customer()
    {
    	return $this->belongsTo(User::class,'customer_id');
    }
}
