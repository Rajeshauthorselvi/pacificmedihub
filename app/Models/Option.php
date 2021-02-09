<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OptionValue;
use DB;
class Option extends Model
{
    protected $table = 'product_options';
    protected $fillable=['option_name'];

    static function  optionvalue($option_id)
    {
    	$option_value=OptionValue::where('option_id',$option_id)
    				  ->where('is_deleted',0)
    				  ->pluck('option_value')->toArray();

    	return $option_value;
    }
}
