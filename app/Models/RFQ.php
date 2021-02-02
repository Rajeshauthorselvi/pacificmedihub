<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class RFQ extends Model
{
    protected $table="rfq";


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
    	return $this->belongsTo(OrderStatus::class,'status');
    }
}
