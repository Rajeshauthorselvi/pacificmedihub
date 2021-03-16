<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Mail\ResetPassword;
use App\User;
use Auth;
use Redirect;
use Session;
use Hash;
use Mail;
use Str;


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

    public function changePassword(Request $request)
    {
        $user = User::where('email',$request->email)->first();   
        if($user){
            $old_pwd = Hash::check($request->old_pwd,$user->password);
            if($old_pwd){
                $user->password = Hash::make($request->con_pwd);
                $user->update();
                Auth::Logout();
                Session::flush();
                return redirect()->route('customer.login');
            }else{
                return Redirect::back()->with('error','Current Password Not Matching, try again!');    
            }
        }else{
            return Redirect::back()->with('error','User does not exist!');
        }
    }


    public function forgetPassword()
    {
        Auth::Logout();
        Session::flush();
        return view('front/customer/forget_password');
    }

    public function resetPassword(Request $request)
    {
        if ($request->ajax()){
            $this->validate(request(), ['email' => 'required']);

            $check = User::where('email',$request->email)->where('role_id',7)->first();

            if(isset($check->email)&&($check->email==$request->email)){
                $code = Str::random(6);

                $add = new PasswordReset;
                $add->email = $request->email;
                $add->token = $request->_token;
                $add->code  = $code;
                $add->created_at = date('Y-m-d H:i:s');
                $add->save();
                if($add){
                    Mail::to($request->email)->send(new ResetPassword($request->email,$code));
                    $data['is_exist'] = true;
                    $data['code']    = $add->code;
                    $data['email']   = $add->email;
                    $data['status']  = true;
                    return response()->json($data);
                }else{
                    $data['is_exist'] = true;
                    $data['message'] = 'Email Not Sent, Please Try Again.!';
                    $data['status']  = false;
                    return response()->json($data);
                }
                
            }else{
                $data['is_exist'] = false;
                $data['message'] = 'User does not exist.!';
                $data['status']  = false;
                return response()->json($data);
            }
        }else{
            $user = User::where('email',$request->email)->first();
            if($user){
                $user->password = Hash::make($request->con_pwd);
                $user->save();
                Session::flush();
                return redirect()->route('customer.login')->with('success', 'Your Password reset successfully.!');
            }else{
                return Redirect::back()->with('error','User does not exist!');
            }
        }
    }
}