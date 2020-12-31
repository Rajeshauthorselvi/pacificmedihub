<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    protected $table = 'product_option_values';

    public function option()
    {
        return $this->belongsTo('App\Models\Option','option_id');
    }
}
