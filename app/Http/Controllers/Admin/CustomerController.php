<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
use App\Models\Countries;
use App\Models\UserCompanyDetails;
use Redirect;
use Arr;
use Session;
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=array();
        $data['all_customers']=User::with('company')
                               ->where('users.role_id',7)
                               ->get();

        return view('admin.customer.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data=array();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();

        $data['all_company']=[''=>'Please Select']+UserCompanyDetails::where('parent_company',0)
                             ->pluck('company_name','id')->toArray();

        return view('admin.customer.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate(request(),[
            '*.email' => 'unique:users'
        ],[
            '*.email.unique'=>'This email already taken'
        ]);

        /*Insert Customer Details*/
        $users=$request->customer;
        $users['role_id']=7;
        $users['status']=1;
        $customer_id=User::insertGetId($users);
        /*Insert Customer Details*/

        /*Insert Bank details*/
        $bank=$request->bank;
        $bank['customer_id']=$customer_id;
        $bacnk_account_id=UserBankAcccount::insertGetId($bank);
        /*Insert Bank details*/

        /*Insert Address Details*/
        $address=$request->address;
        $address['customer_id']=$customer_id;
        $address_id=UserAddress::insertGetId($address);
        /*Insert Address Details*/

        /*Insert Company Details*/
        $company=$request->company;
        $company['parent_company']=isset($company['parent_company'])?$company['parent_company']:0;
        $company['customer_id']=$customer_id;
        $company['created_at']=date('Y-m-d H:i:s');
        Arr::forget($company,['logo']);
        $company_id=UserCompanyDetails::insertGetId($company);
        /*Insert Company Details*/

        /*Update Company , Address,Bank to users table*/
        $update_details=User::find($customer_id);
        $update_details->company_id=$company_id;
        $update_details->address_id=$address_id;
        $update_details->bank_account_id=$bacnk_account_id;
        $update_details->save();
        /*Update Company , address,bank to users table*/

        return Redirect::route('customer.index')->with('success','Customer added successfully...!');

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
        $data=array();
        $data['customer']=$customer=User::with('alladdress','company','bank')
                               ->where('users.role_id',7)
                               ->where('id',$id)
                               ->first();
        $data['all_company']=[''=>'Please Select']+UserCompanyDetails::where('parent_company',0)
        ->where('id','<>',$customer->company_id)
        ->pluck('company_name','id')
        ->toArray();
        return view('admin.customer.edit',$data);
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
            '*.email' => 'unique:users,email,'.$id
        ],[
            '*.email.unique'=>'This email already taken'
        ]);

        $users=$request->customer;
        $customer=User::find($id);
        $customer->update($users);


        $bank_details=$request->bank;
        $bank=UserBankAcccount::find($bank_details['account_id']);
        Arr::forget($bank_details,['account_id']);
        $bank->update($bank_details);

        $company_details=$request->company;
        $company=UserCompanyDetails::find($company_details['company_id']);
        Arr::forget($company_details,['company_id']);
        $company->update($company_details);

       
       return Redirect::route('customer.index')->with('success','Customer details updated successfully...!');


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

    public function AddNewAddressController(Request $request)
    {
        
        $address=$request->except(['_token']);
        $address_id=UserAddress::insertGetId($address);
        $address['address_id']=$address_id;
        Session::flash('from', 'address');
        return ['status'=>true];
    }
}
