<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function optionName1()
    {
        return $this->belongsTo('App\Models\Option','product_option_id1');
    }

    public function optionName2()
    {
        return $this->belongsTo('App\Models\Option','product_option_id2');
    }

    public function optionName3()
    {
        return $this->belongsTo('App\Models\Option','product_option_id3');
    }


    public function optionValue1()
    {
        return $this->belongsTo('App\Models\OptionValue','product_option_value_id1');
    }

    public function optionValue2()
    {
        return $this->belongsTo('App\Models\OptionValue','product_option_value_id2');
    }

    public function optionValue3()
    {
        return $this->belongsTo('App\Models\OptionValue','product_option_value_id3');
    }

    public function vendorName()
    {
        return $this->belongsTo('App\Models\Vendor','vendor_id');
    }
}
