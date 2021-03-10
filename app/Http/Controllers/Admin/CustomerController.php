<?php

namespace App\Http\Controllers\Admin;

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

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','read')) {
                abort(404);
            }
        }

        $data=array();
        $data['all_customers']=User::with('company')
                               ->where('users.role_id',7)
                               ->where('is_deleted',0)
                               ->orderBy('id','desc')
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
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','create')) {
                abort(404);
            }
        }

        $data=array();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();

        $data['all_company']=[''=>'Please Select']+UserCompanyDetails::where('parent_company',0)
                             ->pluck('company_name','id')->toArray();

        $data['sales_rep']= [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();

        $data['customer_code']='';

        $data['customer_code']= '';
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
            $data['customer_code']=$replace_number;
        }


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
        $users['status']=($request->customer['status']=='on')?1:0;
        $users['created_at']=date('Y-m-d H:i:s');
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
        
        if(isset($request->company['logo'])){
            $photo          = $request->company['logo'];     
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->company['logo']->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->company['logo']->move(public_path('theme/images/customer/company/'.$company_id.'/'), $logo_image_name);
            UserCompanyDetails::where('id',$company_id)->update(['logo'=>$logo_image_name]);
        }
        if(isset($request->company['company_gst_certificate'])){
            $gst_file          = $request->company['company_gst_certificate'];     
            $gst_filename       = $gst_file->getClientOriginalName();            
            $gst_file_extension = $request->company['company_gst_certificate']->getClientOriginalExtension();
            $gst_file_name = strtotime("now").".".$gst_file_extension;
            $request->company['company_gst_certificate']->move(public_path('theme/images/customer/company/'.$company_id.'/'), $gst_file_name);
            UserCompanyDetails::where('id',$company_id)->update(['company_gst_certificate'=>$gst_file_name]);
        }
        /*Insert Company Details*/

        /*Update Company , Address,Bank to users table*/
        $update_details=User::find($customer_id);
        $update_details->company_id=$company_id;
        $update_details->address_id=$address_id;
        $update_details->bank_account_id=$bacnk_account_id;
        $update_details->save();
        /*Update Company , address,bank to users table*/

        return Redirect::route('customers.index')->with('success','Customer added successfully...!');

    }   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','read')) {
                abort(404);
            }
        }

        $data=array();
        $data['customer'] = User::with('alladdress','company','bank')
                            ->where('users.role_id',7)
                            ->where('id',$id)
                            ->first();
        // dd($data);
        return view('admin/customer/show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','update')) {
                abort(404);
            }
        }

        $data=array();
        $data['customer']=$customer=User::with('alladdress','company','bank')
                               ->where('users.role_id',7)
                               ->where('id',$id)
                               ->first();
        $data['all_company']=[''=>'Please Select']+UserCompanyDetails::where('parent_company',0)
        ->where('id','<>',$customer->company_id)
        ->pluck('company_name','id')
        ->toArray();
        $data['sales_rep']= [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();
        //dd($data);
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
        $users['status']=($request->customer['status']=='on')?1:0;
        $customer=User::where('id',$id)->update($users);

        $bank_details=$request->bank;
        $bank=UserBankAcccount::find($bank_details['account_id']);
        Arr::forget($bank_details,['account_id']);
        $bank->update($bank_details);

        $company_details=$request->company;

        $company=UserCompanyDetails::find($company_details['company_id']);
        Arr::forget($company_details,['company_id']);
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
       
       return Redirect::route('customers.index')->with('success','Customer details updated successfully...!');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $check_cus = User::find($id);
        if($check_cus){
            $check_cus->is_deleted = 1;
            $check_cus->deleted_at = date('Y-m-d H:i:s');
            $check_cus->update();
            //$cus_cpy = UserCompanyDetails::where('customer_id',$id)->delete();
            //$cus_bank = UserBankAcccount::where('customer_id',$id)->delete();
        }
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('customers.index')->with('error','customer deleted successfully...!');
    }

    public function AddNewAddressController(Request $request)
    {
        //dd($request->all());
        $address=$request->except(['_token']);
        $address_id=UserAddress::insertGetId($address);
        $address['address_id']=$address_id;
        Session::flash('from', 'address');
        return ['status'=>true];
    }

    public function editAddressForm(Request $request)
    {
        $data = array();
        $address = UserAddress::find($request->add_id);
        $data['id'] = $address->id;
        $data['cus_id'] = $address->customer_id;
        $data['name'] = $address->name;
        $data['mobile'] = $address->mobile;
        $data['address_line1'] = $address->address_line1;
        $data['address_line2'] = $address->address_line2;
        $data['post_code'] = $address->post_code;
        $data['country_id'] = $address->country_id;
        $data['state_id'] = $address->state_id;
        $data['city_id'] = $address->city_id;
        $data['latitude'] = $address->latitude;
        $data['longitude'] = $address->longitude;
        $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
        return view('admin.customer.edit_address',$data);
    }

    public function saveAddressForm(Request $request)
    {
        $address = UserAddress::find($request->add_id);
        $address->name = $request->name;
        $address->mobile = $request->mobile;
        $address->address_line1 = $request->address1;
        $address->address_line2 = $request->address2;
        $address->post_code = $request->postcode;
        $address->country_id = $request->country_id;
        $address->state_id = $request->state_id;
        $address->city_id = $request->city_id;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;

        $address->update();
        Session::flash('from', 'address');
        return redirect()->route('customers.edit',$request->cus_id)->with('info','Address Modified successfully.!');
    }
}
