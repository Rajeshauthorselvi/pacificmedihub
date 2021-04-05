<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Mail\ResetPassword;
use App\Models\Countries;
use App\Models\Prefix;
use App\Models\UserCompanyDetails;
use App\Models\UserBankAcccount;
use App\Models\UserPoc;
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
        if(Auth::check()){
            return redirect()->route('home.index');
        }
    	return view('front/customer/customer_login');
    }

    public function store(Request $request)
    {
    	$this->validate(request(), [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        $user_data = User::where('email',$request->email)->first();
        if($user_data){
	        $role_id = isset($user_data->role_id) ? $user_data->role_id : 0;
            $status = isset($user_data->status) ? $user_data->status : 0;
	        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role_id'=>$role_id, 'status'=>$status])) 
	        {    
	            if($role_id==7){
                    if($status==1){
	                   return redirect()->route('home.index');
                    }else{
                        Auth::Logout();
                        return Redirect::back()->withInput($request->only('email'))->with('error','Your account is blocked!');    
                    }
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

    public function newCustomerPage()
    {
        $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
        return view('front/customer/new_register',$data);
    }

    public function newCustomerStore(Request $request)
    {
        $this->validate(request(), ['email' => 'required|email|max:255|unique:users']);
        
        $customer_code = Prefix::where('key','prefix')->where('code','customer')->value('content');
        if (isset($customer_code)) {
            $value = unserialize($customer_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = User::where('role_id',7)->count();
            $total_datas_count = $total_datas+1;

            if(strlen($total_datas_count)==1){
                $start_number = '0000'.$total_datas_count;
            }else if(strlen($total_datas_count)==2){
                $start_number = '000'.$total_datas_count;
            }else if(strlen($total_datas_count)==3){
                $start_number = '00'.$total_datas_count;
            }else if(strlen($total_datas_count)==4){
                $start_number = '0'.$total_datas_count;
            }else{
                $start_number = $total_datas_count;
            }
            $replace_year = str_replace('[yyyy]', $year, $char_val);
            $replace_number = str_replace('[Start No]', $start_number, $replace_year);
        }

        $add_user = User::updateOrCreate([
            'email'          => $request->email,
        ],[
            'role_id'        => 7,
            'name'           => $request->name,
            'email'          => $request->email,
            'contact_number' => $request->contact,
            'address_1'      => $request->company_address,
            'country_id'     => $request->country_id,
            'state_id'       => isset($request->state_id)?$request->state_id:null,
            'city_id'        => isset($request->city_id)?$request->city_id:null,
            'post_code'      => $request->post_code,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'customer_no'    => $replace_number,
            'request_from'   => 2,
            'appoved_status' => 1
        ]);
        
        if($add_user){
            $add_bank = UserBankAcccount::updateOrCreate([
                'customer_id' => $add_user->id,
            ],[
                'account_name'   => null,
                'account_number' => null,
                'bank_name'      => null,
                'bank_branch'    => null,
                'ifsc_code'      => null,
                'paynow_contact' => null,
                'place'          => null,
                'others'         => null
            ]);
            if($add_bank){
                User::where('id',$add_user->id)->update(['bank_account_id'=>$add_bank->id]);
            }
            $add_poc = UserPoc::updateOrCreate([
                'customer_id'     => $add_user->id,
            ],[
                'name'            => $request->name,
                'email'           => $request->email,
                'contact_number'  => $request->contact,
                'timestamps'      => false
            ]);
        }
        return view('front/customer/new_register_success');
    }
}