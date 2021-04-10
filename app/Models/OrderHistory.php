<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $table="order_history";

    static function CheckIfLoad($order_id)
    {
    	 return self::where('order_id',$order_id)->where('order_status_id',14)->exists();
    }
}
