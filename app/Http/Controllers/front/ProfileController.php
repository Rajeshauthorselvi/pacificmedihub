<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
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
    public function index($id)
    {
    	if(!Auth::check()){
    		return redirect()->route('customer.login')->with('info', 'You must be logged in!');
    	}
    	$data=array();
        $data['customer'] = User::with('alladdress','company','bank')
                            ->where('users.role_id',7)
                            ->where('id',$id)
                            ->first();
    	return view('front/customer/profile',$data);
    }

    public function edit($id)
    {
    	if(!Auth::check()){
    		return redirect()->route('customer.login')->with('info', 'You must be logged in!');
    	}
    	$data=array();
        $data['customer'] = User::with('alladdress','company','bank')
                            ->where('users.role_id',7)
                            ->where('id',$id)
                            ->first();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
    	return view('front/customer/edit_profile',$data);
    }

    public function update(Request $request,$id)
    {
        $users=$request->customer;
        $customer=User::where('id',$id)->update($users);

        $bank_details=$request->bank;
        $bank=UserBankAcccount::find($bank_details['account_id']);
        Arr::forget($bank_details,['account_id']);
        $bank->update($bank_details);

        $company_details=$request->company;

        $company=UserCompanyDetails::find($company_details['company_id']);
        Arr::forget($company_details,['company_id']);
        $company['country_id']=($request->country)?1:0;
        $company->update($company_details);

        $logo_image=isset($request->company['logo'])?$request->company['logo']:null;
        if($logo_image!=null){
            $company_id=$request->company['company_id'];
            $check_image=UserCompanyDetails::where('id',$company_id)->value('logo');
            File::delete(public_path('theme/images/customer/company/'.$company_id.'/'.$check_image));

            $photo          = $request->company['logo'];     
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->company['logo']->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->company['logo']->move(public_path('theme/images/customer/company/'.$company_id.'/'), $logo_image_name);
            UserCompanyDetails::where('id',$company_id)->update(['logo'=>$logo_image_name]);
        
        }
       return Redirect::route('profile.index',$id)->with('success','Your details updated successfully...!');
    }
}
