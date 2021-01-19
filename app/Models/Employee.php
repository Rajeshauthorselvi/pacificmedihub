<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function department()
    {
    	return $this->belongsTo('App\Models\Department','emp_department');
    }

    public function city()
    {
    	return $this->belongsTo('App\Models\City','emp_city');
    }
}
