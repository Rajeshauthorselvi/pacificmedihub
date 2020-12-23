<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    public function parent()
    {
        return $this->belongsTo('App\Models\Categories','parent_category_id');
    }
}
