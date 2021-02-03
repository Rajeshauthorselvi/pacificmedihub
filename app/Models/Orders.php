<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Employee;
use App\Models\OrderStatus;
class Orders extends Model
{
    public function customer()
    {
    	return $this->belongsTo(User::class,'customer_id');
    }

    public function salesrep()
    {
    	return $this->belongsTo(Employee::class,'sales_rep_id');
    }
    public function statusName()
    {
    	return $this->belongsTo(OrderStatus::class,'order_status');
    }
}
