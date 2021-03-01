<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Auth;
use DB;
class EmployeeAuth extends Authenticatable
{
	use Notifiable;

	protected $table="employees";

	protected $guard = 'employee';

    protected $fillable = [ 'name', 'email', 'password' ];
  protected $hidden = [ 'password' ];


   public function isAuthorized($object, $operation,$type="single")
    {
        if (Auth::guard('employee')->check()) {
            $employee_role=Auth::guard('employee')->user()->role_id;
            $permission=DB::table('role_access_permissions as rap')
                        ->leftjoin('roles as r','r.id','rap.role_id')
                        ->where('rap.allow_access','yes')
                        ->where('object',$object)
                        ->where('operation', $operation)
                        ->where('rap.role_id', $employee_role)
                        ->exists();
                        // dd($permission,$employee_role);

        }
        else{
            $permission=true;
        }


        return $permission;

    }
}
