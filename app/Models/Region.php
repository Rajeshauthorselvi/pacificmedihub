<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DeliveryZone;
use DB;

class Region extends Model
{
    protected $table="region";

    static function getPostcode($id)
    {
    	$post_code = DeliveryZone::where('region_id',$id)->where('published',1)->pluck('post_code')->toArray();

    	return $post_code;
    }
}
