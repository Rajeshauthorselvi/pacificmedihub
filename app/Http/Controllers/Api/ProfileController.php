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
        $profile_data['sales_rep_id'] 	= $profile->sales_rep;
        $profile_data['logo_url'] 		= url('theme/images/customer/company/');
        $profile_data['dummy_image']	= url('theme/images/products/placeholder.jpg');

        $data['profile_data']  = $profile_data;
        $data['bank_data']	   = UserBankAcccount::where('customer_id',$id)->first();
        $data['poc_data'] 	   = UserPoc::where('customer_id',$id)->get();
        return response()->json(['success'=> true,'data'=>$data]);
    }
}
