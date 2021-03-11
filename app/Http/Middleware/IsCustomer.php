<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Redirect;
use Session;

class IsCustomer
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
        /*if (!Auth::guard('employee')->check() && !Auth::guard('superAdmin')->check()) {
            Session::flash('info', 'You must be logged in!');
            return Redirect::to('/login');
        }elseif(!Auth::check()){
            Session::flash('info', 'You must be logged in!');
            return Redirect::to('/customer-login');
        }else{
            return $next($request);    
        }*/
        return $next($request);    
    }
}
