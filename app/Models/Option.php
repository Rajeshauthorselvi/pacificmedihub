<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OptionValue;
use DB;
class Option extends Model
{
    protected $table = 'product_options';

    static function  optionvalue($option_id)
    {
    	$option_value=OptionValue::where('option_id',$option_id)
    				  ->pluck('option_value')->toArray();

    	return $option_value;
    }
}
