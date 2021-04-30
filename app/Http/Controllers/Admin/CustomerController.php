<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\UserBankAcccount;
use App\Models\UserAddress;
use App\Models\UserPoc;
use App\Models\Countries;
use App\Models\Prefix;
use App\Models\Employee;
use App\Models\NewsLetter;
use App\Mail\NewRegister;
use Redirect;
use Arr;
use Session;
use File;
use Auth;
use Hash;
use Mail;
use Str;
use Excel;
use Response;
use App\Imports\CustomerImport;
use App\Exports\customer\CustomerExport;
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
        $data['all_customers'] = User::where('users.role_id',7)->where('appoved_status',3)->where('status',1)->where('is_deleted',0)->orderBy('id','desc')->get();
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
        $data['countries']     = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
        $data['sales_rep']     = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                                     ->where('emp_department',1)->pluck('emp_name','id')->toArray();
        $data['all_company']   = [''=>'Please Select']+User::where('parent_company',0)->where('role_id',7)->pluck('name','id')->toArray();
        $data['customer_code'] = '';
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
            $data['customer_code'] = $replace_number;
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

        if(isset($request->company['status']) && $request->company['status']=='on') $status = 1;
        else $status = 0;

        if(isset($request->company['parent_company']) && $request->company['parent_company']!=null){
            $parent_company = $request->company['parent_company'];
        }
        else{
            $parent_company=0;
        }

        /* Insert Customer Details */
        $users = $request->company;
        $users['role_id']        = 7;
        $users['status']         = $status;
        $users['parent_company'] = $parent_company;
        $users['request_from']   = 1;
        $users['appoved_status'] = 3;
        $users['created_at']     = date('Y-m-d H:i:s');
        $customer_id             = User::insertGetId($users);
        
        /* Insert Poc details */
        $poc = $request->poc;
        if($request->poc){
            $poc_data = [];
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
                if($value['name']!=null){
                    $add_poc = new UserPoc;
                    $add_poc->customer_id    = $customer_id;
                    $add_poc->name           = $value['name'];
                    $add_poc->email          = $value['email'];
                    $add_poc->contact_number = $value['contact'];
                    $add_poc->timestamps     = false;
                    $add_poc->save();
                }
            }
        }

        /* Insert Bank details */
        $bank = $request->bank;
        $bank['customer_id'] = $customer_id;
        $bacnk_account_id    = UserBankAcccount::insertGetId($bank);

        /* Insert Address Details */
        $address = $request->address;
        $address['customer_id'] = $customer_id;
        $address_id             = UserAddress::insertGetId($address);

        if(isset($request->company['logo'])){
            $photo           = $request->company['logo'];     
            $filename        = $photo->getClientOriginalName();            
            $file_extension  = $request->company['logo']->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->company['logo']->move(public_path('theme/images/customer/company/'.$customer_id.'/'), $logo_image_name);
            User::where('id',$customer_id)->update(['logo'=>$logo_image_name]);
        }

        if(isset($request->company['company_gst_certificate'])){
            $gst_file           = $request->company['company_gst_certificate'];     
            $gst_filename       = $gst_file->getClientOriginalName();            
            $gst_file_extension = $request->company['company_gst_certificate']->getClientOriginalExtension();
            $gst_file_name      = strtotime("now").".".$gst_file_extension;
            $request->company['company_gst_certificate']->move(public_path('theme/images/customer/company/'.$customer_id.'/'), $gst_file_name);
            User::where('id',$customer_id)->update(['company_gst_certificate'=>$gst_file_name]);
        }

        /*Update [Address, Bank] to users table*/
        $update_details = User::find($customer_id);
        $update_details->address_id      = $address_id;
        $update_details->bank_account_id = $bacnk_account_id;
        $update_details->save();

        if($status==1){
            $password = Str::random(6);
            $customer = User::find($customer_id);
            $customer->password = Hash::make($password);
            $customer->mail_sent_status = 1;
            $customer->save();
            Mail::to($customer->email)->send(new NewRegister($customer->name, $customer->email,$password));
        }
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
        $data['customer'] = User::with('alladdress','bank')
                            ->where('users.role_id',7)
                            ->where('id',$id)
                            ->first();
        $data['customer_poc'] = UserPoc::where('customer_id',$id)->get();
        return view('admin/customer/show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','update')) {
                abort(404);
            }
        }

        $data=array();
        $data['customer'] = $customer = User::with('alladdress','bank')->where('users.role_id',7)->where('id',$id)
                                            ->first();
        $data['customer_poc'] = UserPoc::where('customer_id',$id)->get();
        
        $data['all_company']  = [''=>'Please Select']+User::where('parent_company',0)->where('role_id',7)
                                            ->whereNotIn('id',[$id])->pluck('name','id')->toArray();
        $data['sales_rep']    = [''=>'Please Select']+Employee::where('is_deleted',0)->where('status',1)
                                  ->where('emp_department',1)->pluck('emp_name','id')->toArray();
        $data['countries']    = [''=>'Please Select']+Countries::pluck('name','id')->toArray();

        $data['from']         = isset($request->from)?$request->from:'';
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

        /* Update Customer details */
        if(isset($request->company['status']) && $request->company['status']=='on') $status = 1 ;
        else $status = 0;

        if(isset($request->company['parent_company']) && $request->company['parent_company']!=null){
            $parent_company = $request->company['parent_company'];
        }
        else{
            $parent_company=0;
        }

        $users = $request->company;
        $users['status']         = $status;
        $users['parent_company'] = $parent_company;
        Arr::forget($users,['company_id']);
        $customer = User::where('id',$id)->update($users);
        
        /* Update Bank details */
        $bank_details = $request->bank;
        $bank = UserBankAcccount::find($bank_details['account_id']);
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

        $logo_image=isset($request->company['logo'])?$request->company['logo']:null;
        if($logo_image!=null){
            $company_id=$request->company['company_id'];
            $check_image=User::where('id',$company_id)->value('logo');
            File::delete(public_path('theme/images/customer/company/'.$company_id.'/'.$check_image));

            $photo          = $request->company['logo'];     
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->company['logo']->getClientOriginalExtension();
            $logo_image_name = strtotime("now").".".$file_extension;
            $request->company['logo']->move(public_path('theme/images/customer/company/'.$company_id.'/'), $logo_image_name);
            User::where('id',$company_id)->update(['logo'=>$logo_image_name]);
        }

        $user_details = User::find($id);
        if(isset($request->approve_status)){
            $user_details->appoved_status = 3;
            $user_details->save();
        }
        if(($status==1) && ($user_details->mail_sent_status!=1)){
            $password = Str::random(6);
            $user_details->password = Hash::make($password);
            $user_details->mail_sent_status = 1;
            $user_details->save();
            Mail::to($user_details->email)->send(new NewRegister($user_details->name, $user_details->email,$password));
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
            /*
            $cus_bank = UserBankAcccount::where('customer_id',$id)->delete();
            $cus_poc = UserPoc::where('customer_id',$id)->delete();
            $cus_address = UserAddress::where('customer_id',$id)->delete();*/
        }
        if($request->from=='reject_list') return redirect()->route('reject.customer')->with('error','Customer deleted successfully...!');
        if ($request->ajax())  return ['status'=>true];
        else return redirect()->route('customers.index')->with('error','Customer deleted successfully...!');
    }

    public function AddNewAddressController(Request $request)
    {
        $check_data = UserAddress::where('customer_id',$request->customer_id)->count();
        $address=$request->except(['_token']);
        $address_id=UserAddress::insertGetId($address);
        if($check_data==0){
            $update_address = User::find($request->customer_id);
            $update_address->address_id = $address_id;
            $update_address->save();
        }
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

    public function rejectOrBlock(Request $request)
    {
        if($request->data=='reject'){
            User::find($request->id)->update(['appoved_status'=>2]);
            return redirect()->route('new.customer')->with('error','Customer rejected successfully...!');
        }elseif($request->data=='block'){
            User::find($request->id)->update(['status'=>0]);
            return redirect()->route('blocked.customer')->with('error','Customer blocked successfully...!');
        }elseif($request->data=='unblock'){
            User::find($request->id)->update(['status'=>1]);
            return redirect()->route('customers.index')->with('success','Customer unblocked successfully...!');
        }
    }

    public function  newCustomerList()
    {
         if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','read')) {
                abort(404);
            }
        }

        $data=array();
        $data['new_request'] = User::where('users.role_id',7)->where('appoved_status',1)->where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin.customer.new_request_index',$data);
    }

    public function  rejectCustomerList()
    {
         if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','read')) {
                abort(404);
            }
        }

        $data=array();
        $data['rejected_customers'] = User::where('users.role_id',7)->where('appoved_status',2)->where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin.customer.rejected_index',$data);
    }

    public function  blockedCustomerList()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','read')) {
                abort(404);
            }
        }

        $data=array();
        $data['blocked_customers'] = User::where('users.role_id',7)->where('appoved_status',3)->where('status',0)->where('is_deleted',0)->orderBy('id','desc')->get();
        return view('admin.customer.blocked_index',$data);
    }

    public function interestedCustomerList()
    {
        if (!Auth::check() && Auth::guard('employee')->check()) {
            if (!Auth::guard('employee')->user()->isAuthorized('customer','read')) {
                abort(404);
            }
        }

        $data=array();
        $data['interested_customers'] = NewsLetter::orderBy('id','desc')->get();
        return view('admin.customer.interested_customer',$data);
    }

    public function CustomerImport()
    {
        $data=array();
        $data['last_customer_id']=User::orderBy('id','DESC')->latest()->value('id');
        return view('admin.customer.customer_import',$data);
    }
    public function CustomerImportPost(Request $request)
    {
        // dd($request->all());
        try {
             Excel::import(new CustomerImport, $request->file('customer_import')); 
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors=[];
            foreach ($e->failures() as $failure) {
                $errors[] = "Error(s) in column " . $failure->attribute() . " at row " . $failure->row() . " with the message : <strong>" . implode($failure->errors()) ."</strong>";
            }
            return redirect()->back()->withErrors($errors);

        }

        return Redirect::back()->with('success','Customer imported successfully');
    }

    public function DownloadSampleImportSheet()
    {
        $attachment="CustomerImport.xls";
        $path=public_path('theme/sample_datas/').$attachment;
        return Response::download($path, $attachment);
    }
    public function EditAddress($address_id)
    {
        $data = array();
        $address = UserAddress::find($address_id);
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
        return view('admin.customer.address.edit',$data);
    }
    public function CustomerExportController()
    {
        return (new CustomerExport())->download('Customers-List.xls');
    }
}
