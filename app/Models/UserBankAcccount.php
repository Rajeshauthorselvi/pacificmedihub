<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankAcccount extends Model
{
    protected $table='bank_accounts';
    protected $fillable =['customer_id','account_name','account_number','bank_name','bank_branch','ifsc_code','paynow_contact','place','others'];	
    public $timestamps=false;
}
