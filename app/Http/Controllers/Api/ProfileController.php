<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
use App\Models\UserPoc;
use App\Models\Countries;
use App\Models\UserCompanyDetails;
use App\Models\Prefix;
use App\Models\Employee;
use Validator;
use Redirect;
use Arr;
use Session;
use File;
use Auth;

class ProfileController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return response()->json(['success'=>false]);
        }
        $id =  Auth::id();
        $data = $profile_data = array();
        $profile = User::where('users.role_id',7)->where('id',$id)->first();

        $profile_data['user_id'] 		= $profile->id;
        $profile_data['name'] 			= $profile->name;
        $profile_data['email'] 			= $profile->email;
        $profile_data['contact_number'] = $profile->contact_number;
        $profile_data['logo'] 			= $profile->logo;
        $profile_data['customer_no'] 	= $profile->customer_no;
        $profile_data['company_uen'] 	= $profile->company_uen;
        $profile_data['address_1'] 		= $profile->address_1;
        $profile_data['address_2'] 		= $profile->address_2;
        $profile_data['post_code'] 		= $profile->post_code;
        $profile_data['country'] 	    = $profile->getCountry->name;
        $profile_data['country_id'] 	= $profile->country_id;
        $profile_data['state'] 			= $profile->getState->name;
        $profile_data['state_id'] 		= $profile->state_id;
        $profile_data['city'] 			= $profile->getCity->name;
        $profile_data['city_id'] 		= $profile->city_id;
        $profile_data['sales_rep'] 		= $profile->getSalesRep->emp_name;
        $profile_data['logo_url'] 		= url('theme/images/customer/company/');
        $profile_data['dummy_image']	= url('theme/images/products/placeholder.jpg');

        $data['profile_data']  = $profile_data;
        $data['bank_data']	   = UserBankAcccount::where('customer_id',$id)->first();
        $data['poc_data'] 	   = UserPoc::where('customer_id',$id)->get();
        return response()->json(['success'=> true,'data'=>$data]);
    }

    public function updateProfile(Request $request)
    {
        $credentials = $request->only('user_id','name','company_uen','contact_number','address_1','country_id','post_code');
        $rules = [
            'user_id'        => 'required',
            'name'           => 'required',
            'company_uen'    => 'required',
            'contact_number' => 'required',
            'address_1'      => 'required',
            'country_id'     => 'required',
            'post_code'      => 'required'
        ];
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            $validation_error_response=array();
            foreach ($rules as $key => $value) {
                if(!empty($validator->messages()->first($key))) $validation_error_response[]=$validator->messages()->first($key);
            }
            return response()->json(['success'=> false, 'errorMessage'=> $validation_error_response]);
        }
        else
        {
            $user_data = User::find($request->user_id);
            $user_data->name            =  $request->name;
            $user_data->company_uen     =  $request->company_uen;
            $user_data->contact_number  =  $request->contact_number;
            $user_data->address_1       =  $request->address_1;
            $user_data->address_2       =  $request->address_2;
            $user_data->country_id      =  $request->country_id;
            $user_data->state_id        =  $request->state_id;
            $user_data->city_id         =  $request->city_id;
            $user_data->post_code       =  $request->post_code;
            $user_data->save();

            $photo = $request->logo;
            if (base64_decode($photo, true) == true  && ($photo!=""))
            {
                $check_image = User::where('id',$request->user_id)->value('logo');
                File::delete(public_path('theme/images/customer/company/'.$request->user_id.'/'.$check_image));
                $filename        = $photo->getClientOriginalName();            
                $file_extension  = $photo->getClientOriginalExtension();
                $logo_image_name = strtotime("now").".".$file_extension;
                $photo->move(public_path('theme/images/customer/company/'.$request->user_id.'/'), $logo_image_name);
                User::where('id',$request->user_id)->update(['logo'=>$logo_image_name]);
            }
            return response()->json(['success'=> true,'message'=> 'Profile updated successfully.!','data'=>$user_data]);
        }
    }

    public function updateBankData(Request $request)
    {
        
    }
}
