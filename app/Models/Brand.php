<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
	// protected $table="brands";

    protected $fillable =  ['name'];

    public function country()
    {
    	return $this->belongsTo('App\Models\Countries','manf_country_id');
    }
}
