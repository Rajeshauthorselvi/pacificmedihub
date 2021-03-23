<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoc extends Model
{
	protected $fillable=['name','email','contact_number'];
    protected $table = 'user_poc';
}
