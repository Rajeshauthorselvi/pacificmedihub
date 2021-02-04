<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use Redirect;

class IsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Auth::check()) {
            Session::flash('info', 'You must be logged in!');
            return Redirect::to('/');
        }elseif(Auth::user()->role_id==1 && Auth::user()->role_id==2){
            return redirect()->route('admin.login');
        }
        elseif(Auth::user()->role_id==3){
            return redirect()->route('logistics.login');
        }elseif(Auth::user()->role_id==4){
            return redirect()->route('salesRep.login');
        }elseif(Auth::user()->role_id==5){
            return redirect()->route('employee.login');
        }elseif(Auth::user()->role_id==6){
            return redirect()->route('deliveryPerson.login');
        }elseif(Auth::user()->role_id==7){
            return redirect()->route('customers.login');
        }else{
            return $next($request);    
        }
    }
}
