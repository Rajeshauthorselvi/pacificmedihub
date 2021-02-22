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

    public function currencyCode()
    {
        return $this->belongsTo(Currency::class,'currency');
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

    public function paidAmount()
    {
        return $this->belongsTo(PaymentMethod::class,'paying_by');
    }
}
