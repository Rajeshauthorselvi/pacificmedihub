<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionValue extends Model
{
    protected $table="commission_values";
    
    public function comission()
    {
    	return $this->belongsTo('App\Models\Commissions','commission_id');
    }
}
