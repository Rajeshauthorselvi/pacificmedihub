<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserBankAcccount;
use App\Models\UserPoc;
use App\Models\Notification;
use App\Models\PasswordReset;
use App\Mail\ResetPassword;
use App\User;
use Auth;
use Validator;
use Mail;
use Str;
use Session;


class AuthController extends Controller
{
    public function login(Request $request)
    {
    	$credentials = $request->only('email','password','device_type','device_token');
    	$rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'device_type'=>'in:ios,android',
            'device_token'=>'max:255'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response,'data'=>'']);
        }
         else
        {
            $user=User::where('email',$request->email)->first();
            if($user->status==0) return response()->json(['success'=> false, 'errorMessage'=>'You are blocked by admin','data'=>'']);
            if($request->has('device_type') && $request->has('device_token')) {
                $user->device_type  = $request->device_type;
                $user->device_token = $request->device_token;
            }
            $user->save();
            $password   = base64_decode($request->password);
            
            if(Auth::attempt(['email' => $request->email, 'password' => $password])) 
	        {
                $tokenResult = $user->createToken('Personal Access Token');
                $token = $tokenResult->token;
                $data['access_token'] = $tokenResult->accessToken;
                $data['token_type'] = 'Bearer';

	            $data['user'] = $user;
                $user->last_login = date('Y-m-d H:i:s');
                $user->update();
	            return response()->json(['success'=> true,'errorMessage'=>'','data'=>$data]);
	        }
	        else
	        {
                $validation_error_response[] = 'Invalid login details';
	            return response()->json(['success'=>false,'errorMessage'=>$validation_error_response,'data'=>'']);
	        }
        }
    }


    public function registerUser(Request $request)
    {
        $credentials = $request->only('name','email','contact','address','country','post_code','device_type','device_token');
        $rules = [
            'name'         => 'required|max:150',
            'email'        => 'required|email|max:255|unique:users',
            'contact'      => 'required',
            'address'      => 'required',
            'country'      => 'required',
            'post_code'    => 'required',
            'device_type'  => 'required|in:ios,android',
            'device_token' => 'required|max:255'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key)))
                $validation_error_response[$key]=$validator->messages()->first($key);                
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response,'data'=>'']);
        }

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
            'address_1'      => $request->address,
            'country_id'     => $request->country,
            'state_id'       => isset($request->state)?$request->state:null,
            'city_id'        => isset($request->city)?$request->city:null,
            'post_code'      => $request->post_code,
            'latitude'       => isset($request->latitude)?$request->latitude:null,
            'longitude'      => isset($request->longitude)?$request->longitude:null,
            'customer_no'    => $replace_number,
            'device_type'    => $request->device_type,
            'device_token'   => $request->device_token,
            'request_from'   => 3,
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

            Notification::insert([
                'type'                => 'register',
                'ref_id'              => $add_user->id,
                'content'             => $request->name.' requested as a customer' ,
                'url'                 => url('admin/customers/'.$add_user->id.'/edit?from=approve'),
                'created_at'          => date('Y-m-d H:i:s'),
                'created_by'          => $add_user->id,
                'created_user_type'   => 3,
            ]);
            return response()->json(['success'=> true, 'errorMessage'=> '','data'=>$add_user]);
        }else{
            $validation_error_response[] = 'Unable to create the user';
            return response()->json(['success'=> false,'errorMessage'=>$validation_error_response,'data'=>'']); 
        } 
    }

    public function sendCode(Request $request)
    {
        $credentials = $request->only('email');
        $rules = [
            'email'        => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key)))
                $validation_error_response[$key]=$validator->messages()->first($key);                
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response,'data'=>'']);
        }

        $check = User::where('email',$request->email)->where('role_id',7)->first();

        if(isset($check->email)&&($check->email==$request->email)){
            $code = Str::random(6);

            $add = new PasswordReset;
            $add->email = $request->email;
            $add->token = $request->device_token;
            $add->code  = $code;
            $add->created_at = date('Y-m-d H:i:s');
            $add->save();
            if($add){
                Mail::to($request->email)->send(new ResetPassword($request->email,$code));
                return response()->json(['success'=> true, 'errorMessage'=>'','data'=>$add]);
            }else{
                return response()->json(['success'=> false, 'errorMessage'=>'Email Not Sent, Please Try Again.!']);
            }
        }else{
            return response()->json(['success'=> false, 'errorMessage'=>'User does not exist.!']);
        }
    }

    public function forgetPassword(Request $request)
    {
        $credentials = $request->only('email','password');
        $rules = [
            'email'        => 'required',
            'password'     => 'required'
        ];
        $check = User::where('email',$request->email)->first();
        if(isset($check->email)&&($check->email==$request->email)){
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json(['success'=> true, 'errorMessage'=>'','data'=>$add]);
        }else{
            return response()->json(['success'=> false, 'errorMessage'=>'Email mismatch, User does not exist.!']);   
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();        
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}