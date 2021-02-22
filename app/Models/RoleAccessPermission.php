<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleAccessPermission extends Model
{
    protected $fillable=['role_id','object','operation','allow_access','created_at'];

    static function CheckRecordExists($role_id)
    {
    	return self::where('role_id',$role_id)->exists();
    }
}
