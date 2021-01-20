<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    public function optionName1()
    {
        return $this->belongsTo('App\Models\Option','option_id');
    }

    public function optionName2()
    {
        return $this->belongsTo('App\Models\Option','option_id2');
    }

    public function optionName3()
    {
        return $this->belongsTo('App\Models\Option','option_id3');
    }

    public function optionName4()
    {
        return $this->belongsTo('App\Models\Option','option_id4');
    }

    public function optionName5()
    {
        return $this->belongsTo('App\Models\Option','option_id5');
    }


    public function optionValue1()
    {
        return $this->belongsTo('App\Models\OptionValue','option_value_id');
    }

    public function optionValue2()
    {
        return $this->belongsTo('App\Models\OptionValue','option_value_id2');
    }

    public function optionValue3()
    {
        return $this->belongsTo('App\Models\OptionValue','option_value_id3');
    }

    public function optionValue4()
    {
        return $this->belongsTo('App\Models\OptionValue','option_value_id4');
    }

    public function optionValue5()
    {
        return $this->belongsTo('App\Models\OptionValue','option_value_id5');
    }

    public function vendorName()
    {
        return $this->belongsTo('App\Models\Vendor','vendor_id');
    }
}
