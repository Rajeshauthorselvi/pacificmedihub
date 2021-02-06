<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable=['name'];
    public function category()
    {
        return $this->belongsTo('App\Models\Categories','category_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand','brand_id');
    }
}
