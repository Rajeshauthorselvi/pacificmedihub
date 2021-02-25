<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class EmployeeAuth extends Authenticatable
{
	use Notifiable;

	protected $table="employees";

	protected $guard = 'employee';

    protected $fillable = [ 'name', 'email', 'password' ];
  protected $hidden = [ 'password' ];
}
