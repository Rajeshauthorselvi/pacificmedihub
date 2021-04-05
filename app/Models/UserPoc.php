<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoc extends Model
{
	protected $fillable=['customer_id','name','email','contact_number'];
    protected $table = 'user_poc';
    public $timestamps = false;
}
