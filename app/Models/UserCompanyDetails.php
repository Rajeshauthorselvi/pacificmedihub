<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCompanyDetails extends Model
{
    protected $table="user_company_details";
    protected $fillable=['customer_id','company_name','parent_company','company_gst','company_uen','telephone','company_email','address_1','address_2','post_code','country_id','state_id','city_id','logo','sales_rep'];
    
    static function ParentCompany($company_id)
    {
    	return self::where('parent_company',$company_id)->value('company_name');
    }

    public function getCountry()
    {
        return $this->belongsTo('App\Models\Countries','country_id');
    }

    public function getState()
    {
        return $this->belongsTo('App\Models\State','state_id');
    }

    public function getCity()
    {
        return $this->belongsTo('App\Models\City','city_id');
    }

    public function getSalesRep()
    {
    	return $this->belongsTo('App\Models\Employee','sales_rep');	
    }
}
