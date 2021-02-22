<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $table='address';

    public function country()
    {
    	return $this->belongsTo('App\Models\Countries','country_id');
    }

    public function state()
    {
    	return $this->belongsTo('App\Models\State','state_id');
    }

    public function city()
    {
    	return $this->belongsTo('App\Models\City','city_id');
    }
}
