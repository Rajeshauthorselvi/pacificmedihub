<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function department()
    {
    	return $this->belongsTo('App\Models\Department','emp_department');
    }

    public function country()
    {
    	return $this->belongsTo('App\Models\Countries','emp_country');
    }

    public function state()
    {
    	return $this->belongsTo('App\Models\State','emp_state');
    }

    public function city()
    {
    	return $this->belongsTo('App\Models\City','emp_city');
    }

    public function baseCommission(){
        return $this->belongsTo('App\Models\CommissionValue','basic_commission_type');
    }

    public function targetCommission(){
        return $this->belongsTo('App\Models\CommissionValue','target_commission_type');
    }
}
