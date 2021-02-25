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
        elseif (Auth::guard('employee')->check()) {
            return redirect()->route('admin.dashboard');
        }
    	return view('admin/auth/login');
    }
    public function store(Request $request)
    {
        Session::flash('error_from','admin');
    	$this->validate(request(), [
            'email'     => 'required|email',
            'password'  => 'required|min:4'       
        ]);
       
        $check_role = User::where('email',$request->email)->first();
        $role_id = isset($check_role->role_id) ? $check_role->role_id : 0;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=>$role_id])) 
        {    
            if($role_id==1){
                Session::forget('error_from');
                return redirect()->route('admin.dashboard');                              
            }
        }
        else
        {
           Session::flash('error_from','admin');
           return Redirect::back()->withInput($request->only('email'))->with('error','Please Check your data...!');
        }
    }
    public function logout()
    {
        Auth::Logout();
        Session::flush();
        return Redirect::to('who-you-are')->with('info','Logout Successfully..!');
    }
}
