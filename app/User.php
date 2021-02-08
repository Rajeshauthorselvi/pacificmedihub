<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
use App\Models\UserCompanyDetails;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    
}
