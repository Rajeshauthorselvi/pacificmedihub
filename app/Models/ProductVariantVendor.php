<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantVendor extends Model
{
    public function vendorName()
    {
        return $this->belongsTo('App\Models\Vendor','vendor_id');
    }
}
