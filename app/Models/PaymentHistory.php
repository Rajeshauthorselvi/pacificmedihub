<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentMethod;
class PaymentHistory extends Model
{
    protected $table="payment_history";

    static function FindPendingBalance($ref_id,$total_amount,$from)
    {
    	$total_paid=self::where('ref_id',$ref_id)->where('payment_from',$from)->sum('amount');
    	$balance_amount=$total_amount-$total_paid;
    	return ['balance_amount'=>$balance_amount,'paid_amount'=>$total_paid];
    }

    public function PaymentMethod()
    {
    	return $this->belongsTo(PaymentMethod::class,'payment_id');
    }
}
