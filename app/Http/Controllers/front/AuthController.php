<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Redirect;
use Session;

class AuthController extends Controller
{
    public function index()
    {
    	return view('front/customer/customer_login');
    }

    public function store(Request $request)
    {
    	$this->validate(request(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $check_role = User::where('email',$request->email)->first();
        if($check_role){
	        $role_id = isset($check_role->role_id) ? $check_role->role_id : 0;
	        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=>$role_id])) 
	        {    
	            if($role_id==7){
	                return redirect()->route('home.index');
	            }else{
	            	return Redirect::back()->withInput($request->only('email'))->with('error','This email does not allow as a User!');
	            }
	        }else{
	           return Redirect::back()->withInput($request->only('email'))->with('error','Please Check your data!');
	        }
	    }else{
	        return Redirect::back()->withInput($request->only('email'))->with('error','User does not exist!');
	    }
    }

    public function logout()
    {
        Auth::Logout();
        Session::flush();
        return redirect()->route('home.index');
    }
}
