<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
use App\Models\UserPoc;
use App\Models\UserCompanyDetails;
use DB;
use Auth;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id','contact_number','address_1','country_id','state_id','city_id','post_code','latitude','longitude','customer_no','request_from','appoved_status','status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(UserCompanyDetails::class,'company_id');
    }

    public function bank()
    {
        return $this->belongsTo(UserBankAcccount::class,'bank_account_id');
    }
    
    public function address()
    {
        return $this->belongsTo(UserAddress::class,'address_id');
    }
    public function alladdress()
    {
        return $this->hasMany(UserAddress::class,'customer_id','id');
    }

    public function poc()
    {
        return $this->belongsTo(UserPoc::class,'poc_id');
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

    static function ParentCompany($parent_id)
    {
        return self::where('id',$parent_id)->where('role_id',7)->value('name');
    }

    static function TotalOrders($customer_id)
    {
        $total_order = DB::table('orders')->where('customer_id',$customer_id)->count();
        return $total_order;
    }

    static function TotalOrderAmount($customer_id)
    {
        $total_order_amount = DB::table('orders')->where('customer_id',$customer_id)->sum('total_amount');
        return $total_order_amount;
    }
}
