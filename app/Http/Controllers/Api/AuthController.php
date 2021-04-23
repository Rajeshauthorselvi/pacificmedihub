<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Validator;

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
	            $data['user']=$user;
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

}
