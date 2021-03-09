<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Employee;
class RFQComments extends Model
{
    protected $table='rfq_comments';
    static function GetUserNames($user_id,$user_type)
    {
    	if ($user_type==1) {
    		$user_details=User::find($user_id);
    	}
    	else{
    		$user_details=Employee::find($user_id);
    	}

    	return $user_details;
    }
}
