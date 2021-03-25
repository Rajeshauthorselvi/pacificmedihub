<?php

namespace App\Http\Controllers\front;

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $data=array();
        $id = $data['user_id']  = Auth::id();
        $data['customer'] = User::with('alladdress','company','bank','poc')
                            ->whereIn('users.role_id',[1,7])
                            ->where('id',$id)
                            ->first();
        //dd($data);
        return view('front/customer/profile',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!Auth::check()){
            return redirect()->route('customer.login')->with('info', 'You must be logged in!');
        }
        $data=array();
        $data['user_id']  = Auth::id();
        $data['customer'] = User::with('alladdress','company','bank','poc')
                            ->where('users.role_id',7)
                            ->where('id',$id)
                            ->first();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        return view('front/customer/edit_profile',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $users    = $request->customer;
        $customer = User::where('id',$id)->update($users);

        $bank_details = $request->bank;
        $bank         = UserBankAcccount::find($bank_details['account_id']);
        Arr::forget($bank_details,['account_id']);
        $bank->update($bank_details);

        /* Update POC details */
        $poc_details = $request->poc;
        $poc = UserPoc::find($poc_details['poc_id']);
        $poc->timestamps = false;
        Arr::forget($poc_details,['poc_id']);
        $poc->update($poc_details);

        $company_details=$request->company;
        $company = UserCompanyDetails::find($company_details['company_id']);
        Arr::forget($company_details,['company_id']);
        $company->update($company_details);

        $logo_image = isset($request->company['logo'])?$request->company['logo']:null;
        if($logo_image!=null){
            $company_id  = $request->company['company_id'];
            $check_image = UserCompanyDetails::where('id',$company_id)->value('logo');
            File::delete(public_path('theme/images/customer/company/'.$company_id.'/'.$check_image));

            $photo           = $request->company['logo'];
            $filename        = $photo->getClientOriginalName();            
            $file_extension  = $request->company['logo']->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->company['logo']->move(public_path('theme/images/customer/company/'.$company_id.'/'), $logo_image_name);
            UserCompanyDetails::where('id',$company_id)->update(['logo'=>$logo_image_name]);
        }
       return Redirect::route('my-profile.index')->with('success','Your details updated successfully...!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
