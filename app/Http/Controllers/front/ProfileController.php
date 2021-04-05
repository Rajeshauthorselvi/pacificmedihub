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
        $data['customer'] = User::with('alladdress','bank')
                            ->whereIn('users.role_id',[1,7])
                            ->where('id',$id)
                            ->first();
        $data['customer_poc'] = UserPoc::where('customer_id',$id)->get();
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
        $data['customer'] = User::with('alladdress','bank')
                            ->where('users.role_id',7)
                            ->where('id',$id)
                            ->first();
        $data['customer_poc'] = UserPoc::where('customer_id',$id)->get();
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
        $users    = $request->company;
        $customer = User::where('id',$id)->update($users);

        /* Update Bank details */
        $bank_details = $request->bank;
        $bank         = UserBankAcccount::find($bank_details['account_id']);
        Arr::forget($bank_details,['account_id']);
        $bank->update($bank_details);


        /* Update POC details */
        if($request->poc){
            $poc_data = [];
            $i = 0;
            foreach ($request->poc['id'] as $poc_id) {
                $poc_data[$i]['id'] = $poc_id;
                $i = $i + 1;
            }
            $i = 0;
            foreach ($request->poc['name'] as $name) {
                $poc_data[$i]['name'] = $name;
                $i = $i + 1;
            }
            $i = 0;
            foreach ($request->poc['email'] as $email) {
                $poc_data[$i]['email'] = $email;
                $i = $i + 1;
            }
            $i = 0;
            foreach ($request->poc['contact'] as $contact) {
                $poc_data[$i]['contact'] = $contact;
                $i = $i + 1;
            }
            foreach ($poc_data as $key => $value) {
                if($value['id']!=0){
                    $update_poc = UserPoc::where('id',$value['id'])->first();
                    $update_poc->name           = $value['name'];
                    $update_poc->email          = $value['email'];
                    $update_poc->contact_number = $value['contact'];
                    $update_poc->timestamps     = false;
                    $update_poc->save();
                }elseif($value['name']!=null){
                    $add_poc = new UserPoc;
                    $add_poc->customer_id    = $id;
                    $add_poc->name           = $value['name'];
                    $add_poc->email          = $value['email'];
                    $add_poc->contact_number = $value['contact'];
                    $add_poc->timestamps     = false;
                    $add_poc->save();
                }
            }
        }

        $logo_image = isset($request->company['logo'])?$request->company['logo']:null;
        if($logo_image!=null){
            $check_image = User::where('id',$id)->value('logo');
            File::delete(public_path('theme/images/customer/company/'.$id.'/'.$check_image));
            $photo           = $request->company['logo'];
            $filename        = $photo->getClientOriginalName();            
            $file_extension  = $request->company['logo']->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->company['logo']->move(public_path('theme/images/customer/company/'.$id.'/'), $logo_image_name);
            User::where('id',$id)->update(['logo'=>$logo_image_name]);
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
