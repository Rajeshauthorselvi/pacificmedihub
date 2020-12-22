<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Session;
use Redirect;

class AuthController extends Controller
{
    public function index()
    {
    	if(Auth::check()) {
           return redirect()->route('admin.dashboard');   
        }
    	return view('admin/auth/login');
    }

    public function store(Request $request)
    {
    	$this->validate(request(), [
            'email'     => 'required|email',
            'password'  => 'required|min:4'       
        ]);
        $check_role = User::where('email',$request->email)->first();
        $role_id = isset($check_role->role_id) ? $check_role->role_id : 0;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=>$role_id])) 
        {    
            if($role_id==1){
                return redirect()->route('admin.dashboard');                              
            }
            else{
                //
            }
        }
        else
        {
           return redirect()->route('admin.login')->with('error','Please Check your data...!');
        }
    }

    public function logout()
    {
        Auth::Logout();
        Session::flush();
        return redirect()->route('admin.login')->with('info','Logout Successfully..!');
    }
}
