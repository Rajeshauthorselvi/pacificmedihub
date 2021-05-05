<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserCompanyDetails;
use Redirect;
use Auth;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Role;
use App\Models\Countries;
use App\Models\CommissionValue;
class AdminProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        if (!Auth::check() && Auth::guard('employee')->check()) {
            $data = array();
            $data['employees'] = Employee::find(Auth::guard('employee')->user()->id);
            $data['departments']=[''=>'Please Select']+Department::where('is_deleted',0)->where('status',1)->pluck('dept_name','id')->toArray();

            $data['roles']=[''=>'Please Select']+Role::whereNotIn('id',[1,7])
                                 ->where('is_delete',0)
                                 ->pluck('name','id')
                                 ->toArray();

            $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
            //Commission Id 1 is a Base Commission
            $data['base_commissions']     = CommissionValue::where('commission_id',1)->where('published',1)
                                                ->where('is_deleted',0)->get();
            //Commission Id 3 is a Target Commission
            $data['target_commissions']   = CommissionValue::where('commission_id',3)->where('published',1)
                                                ->where('is_deleted',0)->get();

            return view('admin.profile..employee_profile.edit',$data);
        }
        else{
            $data['admin'] = User::where('role_id',1)->first();
            return view('admin.profile.form',$data);
        }

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
        //
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
        $this->validate(request(),[
            'contact_number' => 'required',
            'gst'            => 'required'
        ]);

       $update_details=User::find($id);
       $update_details->name       = $request->name;
       $update_details->latitude       = $request->latitude;
       $update_details->longitude      = $request->longitude;
       $update_details->address_1      = $request->address1;
       $update_details->address_2      = $request->address2;
       $update_details->contact_number = $request->contact_number;
       $update_details->company_gst    = $request->gst;
       
        if ($request->hasFile('logo')) {
            $imageName = time().'.'.$request->logo->extension();  
            $request->logo->move(public_path('theme/images/profile'), $imageName);
            $update_details->logo    = $imageName;
        }

        $update_details->save();
       
       return Redirect::back()->with('success','Profile details updated successfully...!');
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
