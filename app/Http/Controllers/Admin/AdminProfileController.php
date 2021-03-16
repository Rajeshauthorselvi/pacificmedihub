<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserCompanyDetails;
use Redirect;
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
        $data['admin'] = User::where('role_id',1)->first();
        $data['company'] = UserCompanyDetails::where('customer_id',1)->first();
        return view('admin.profile.form',$data);
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
            'contact_number'     => 'required',
            'gst'     => 'required'
        ]);


       $address_details=UserCompanyDetails::find($id);
       $address_details->latitude=$request->latitude;
       $address_details->longitude=$request->longitude;
       $address_details->address_1=$request->address_1;
       $address_details->address_2=$request->address2;
       $address_details->telephone=$request->contact_number;
       // $address_details->last_name=$request->last_name;
       $address_details->company_gst=$request->gst;
       $address_details->save();

       $user_details=User::find(2);
       // $user_details->first_name=$request->first_name;
       // $user_details->last_name=$request->last_name;
       $user_details->contact_number=$request->contact_number;
       $user_details->save();

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
