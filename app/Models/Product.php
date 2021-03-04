<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $fillable=['name','code','sku','category_id','brand_id','main_image','short_description','long_description','alert_quantity','commission_type','commission_value','published','show_home','search_engine_name','meta_title','meta_keyword','meta_description','created_at','updated_at','is_deleted','deleted_at'];
    public function category()
    {
        return $this->belongsTo('App\Models\Categories','category_id');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand','brand_id');
    }

    public function commissionType(){
        return $this->belongsTo('App\Models\CommissionValue','commission_type');
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class, 'product_id')->where('is_deleted',0)->orderBy('display_order','desc');
    }

    public function sorted_product_image(){
        return $this->product_images()->where('display_order',1);
    }

    public function product_variant(){
        return $this->hasMany(ProductVariant::class,'product_id');
    }

    public function order_product_variant(){
        return $this->hasOne(ProductVariant::class,'product_id');
    }
}
