<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use App\Models\Settings;
use App\Models\EmpSalaryHistory;
use Session;
use Redirect;
use Arr;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $data['employees'] = Employee::where('is_deleted',0)->orderBy('created_at','desc')->get();
        return view('admin.employees.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();
        $data['departments']=[''=>'Please Select']+Department::where('is_deleted',0)->where('status',1)->pluck('dept_name','id')->toArray();

        $data['countries']=[''=>'Please Select']+Countries::pluck('name','id')->toArray();

        $key_val=Settings::where('key','prefix')
                 ->where('code','employee')
                 ->value('content');
        $data['employee_id']='';
        if (isset($key_val)) {
            $value=unserialize($key_val);

            $char_val=$value['value'];
            $total_datas=Employee::count();
            $data_original='EMP-[dd]-[mm]-[yyyy]-[Start No]';

            $search=['[dd]', '[mm]', '[yyyy]', '[Start No]'];
            $replace=[date('d'), date('m'), date('Y'), $total_datas+1 ];

            $data['employee_id']=str_replace($search,$replace, $data_original);

        }

        return view('admin.employees.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'emp_name'       => 'required',
            'dept_id'        => 'required',
            'designation'    => 'required',
            'emp_email'      => 'required|email',
            'emp_contact'    => 'required',
            'address1'       => 'required',
            'postcode'       => 'required',
            'country_id'     => 'required',
            'account_name'   => 'required',
            'account_number' => 'required',
            'bank_name'      => 'required',
            'bank_branch'    => 'required',
            'ifsc'           => 'required',
            'basic'          => 'required'
        ]);

        $emp_image= $request->hasFile('emp_image');
        if($emp_image){
            $photo          = $request->file('emp_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('emp_image')->getClientOriginalExtension();
            $image_name = strtotime("now").".".$file_extension;
            $request->emp_image->move(public_path('theme/images/employees/'), $image_name);
        }
        else{
            $image_name = NULL;
        }

        if($request->emp_status){$status = 1;}else{$status = 0;}

        $add_emp = new Employee;
        $add_emp->emp_id = $request->emp_id;
        $add_emp->emp_name = $request->emp_name;
        $add_emp->emp_department = $request->dept_id;
        $add_emp->emp_designation = $request->designation;
        $add_emp->emp_identification_no = $request->identification_no;
        $add_emp->emp_doj = date('Y-m-d',strtotime($request->doj_date));
        $add_emp->emp_mobile_no = $request->emp_contact;
        $add_emp->emp_email = $request->emp_email;
        $add_emp->emp_address_line1 = $request->address1;
        $add_emp->emp_address_line2 = $request->address2;
        $add_emp->emp_postcode = $request->postcode;
        $add_emp->emp_country = $request->country_id;
        $add_emp->emp_state = $request->state_id;
        $add_emp->emp_city = $request->city_id;
        $add_emp->emp_image = $image_name;
        $add_emp->emp_account_name = $request->account_name;
        $add_emp->emp_account_number = $request->account_number;
        $add_emp->emp_bank_name = $request->bank_name;
        $add_emp->emp_bank_branch = $request->bank_branch;
        $add_emp->emp_ifsc_code = $request->ifsc;
        $add_emp->emp_paynow_contact_number = $request->paynow_no;
        $add_emp->basic = $request->basic;
        $add_emp->hr = $request->hra;
        $add_emp->da = $request->da;
        $add_emp->conveyance = $request->conveyance;
        $add_emp->esi = $request->esi;
        $add_emp->pf = $request->pf;
        $add_emp->basic_commission_type = $request->commision_type;
        $add_emp->basic_commission_value = $request->commision_value;
        $add_emp->target_value = $request->target_value;
        $add_emp->target_commission_type = $request->target_commision_type;
        $add_emp->target_commission_value = $request->target_commission_value;
        $add_emp->status = $status;
        $add_emp->created_at = date('Y-m-d H:i:s');
        $add_emp->save();

        if($add_emp){
            $salary_history = new EmpSalaryHistory;
            $salary_history->emp_id = $add_emp->id;
            $salary_history->basic = $request->basic;
            $salary_history->hr = $request->hra;
            $salary_history->da = $request->da;
            $salary_history->conveyance = $request->conveyance;
            $salary_history->esi = $request->esi;
            $salary_history->pf = $request->pf;
            $salary_history->modified_date = date('Y-m-d');
            $salary_history->save();

            return Redirect::route('employees.index')->with('success','Employee added successfully.!');    
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = array();
        $data['employees'] = Employee::find($id);
        return view('admin.employees.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = array();
        $data['employees'] = Employee::find($id);
        $data['departments'] = [''=>'Please Select']+Department::where('is_deleted',0)->where('status',1)->pluck('dept_name','id')->toArray();
        $data['countries'] = [''=>'Please Select']+Countries::pluck('name','id')->toArray();
        return view('admin.employees.edit',$data);
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
        $this->validate(request(), [
            'emp_name'       => 'required',
            'dept_id'        => 'required',
            'designation'    => 'required',
            'emp_email'      => 'required|email',
            'emp_contact'    => 'required',
            'address1'       => 'required',
            'postcode'       => 'required',
            'country_id'     => 'required',
            'account_name'   => 'required',
            'account_number' => 'required',
            'bank_name'      => 'required',
            'bank_branch'    => 'required',
            'ifsc'           => 'required',
            'basic'          => 'required'
        ]);

        $update_emp = Employee::find($id);

        $emp_image= $request->hasFile('emp_image');
        if($emp_image){
            $photo          = $request->file('emp_image');            
            $filename       = $photo->getClientOriginalName();            
            $file_extension = $request->file('emp_image')->getClientOriginalExtension();
            $image_name = strtotime("now").".".$file_extension;
            $request->emp_image->move(public_path('theme/images/employees/'), $image_name);
        }
        else{
            $image_name = $update_emp->emp_image;
        }

        if($request->emp_status){$status = 1;}else{$status = 0;}

        $update_emp->emp_id = $request->emp_id;
        $update_emp->emp_name = $request->emp_name;
        $update_emp->emp_department = $request->dept_id;
        $update_emp->emp_designation = $request->designation;
        $update_emp->emp_identification_no = $request->identification_no;
        $update_emp->emp_doj = date('Y-m-d',strtotime($request->doj_date));
        $update_emp->emp_mobile_no = $request->emp_contact;
        $update_emp->emp_email = $request->emp_email;
        $update_emp->emp_address_line1 = $request->address1;
        $update_emp->emp_address_line2 = $request->address2;
        $update_emp->emp_postcode = $request->postcode;
        $update_emp->emp_country = $request->country_id;
        $update_emp->emp_state = $request->state_id;
        $update_emp->emp_city = $request->city_id;
        $update_emp->emp_image = $image_name;
        $update_emp->emp_account_name = $request->account_name;
        $update_emp->emp_account_number = $request->account_number;
        $update_emp->emp_bank_name = $request->bank_name;
        $update_emp->emp_bank_branch = $request->bank_branch;
        $update_emp->emp_ifsc_code = $request->ifsc;
        $update_emp->emp_paynow_contact_number = $request->paynow_no;
        $update_emp->basic = $request->basic;
        $update_emp->hr = $request->hra;
        $update_emp->da = $request->da;
        $update_emp->conveyance = $request->conveyance;
        $update_emp->esi = $request->esi;
        $update_emp->pf = $request->pf;
        $update_emp->basic_commission_type = $request->commision_type;
        $update_emp->basic_commission_value = $request->commision_value;
        $update_emp->target_value = $request->target_value;
        $update_emp->target_commission_type = $request->target_commision_type;
        $update_emp->target_commission_value = $request->target_commission_value;
        $update_emp->status = $status;
        $update_emp->update();

        if($update_emp){
            $emp_salary_history = EmpSalaryHistory::where('emp_id',$id)->latest('modified_date')->first();
            if($emp_salary_history->basic != $request->basic)
            {
                $salary_history = new EmpSalaryHistory;
                $salary_history->emp_id = $update_emp->id;
                $salary_history->basic = $request->basic;
                $salary_history->hr = $request->hra;
                $salary_history->da = $request->da;
                $salary_history->conveyance = $request->conveyance;
                $salary_history->esi = $request->esi;
                $salary_history->pf = $request->pf;
                $salary_history->modified_date = date('Y-m-d');
                $salary_history->save();
            }
            return Redirect::route('employees.index')->with('success','Employee details updated successfully.!');    
        }else{
            return Redirect::back()->with('error','Somthing wrong please try again...!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Employee $employee)
    {
        $employees = Employee::find($employee->id);
        $employees->is_deleted=1;
        $employees->deleted_at = date('Y-m-d H:i:s');
        $employees->save();

        if ($request->ajax())  return ['status'=>true];
        else
           return Redirect::route('employees.index')->with('success','Employee deleted successfully.!');
    }

    public function salaryList()
    {
        $data = array();
        $get_employees = Employee::where('status',1)->where('is_deleted',0)->get();
        $employee = array();
        foreach ($get_employees as $key => $emp) {
            $employee[$key]['id'] = $emp->id;
            $employee[$key]['name'] = $emp->emp_name;
            $employee[$key]['basic_salary'] = $emp->basic;
            $employee[$key]['hra'] = $emp->hr;
            $employee[$key]['da'] = $emp->da;
            $employee[$key]['conveyance'] = $emp->conveyance;
            $employee[$key]['esi'] = $emp->esi;
            $employee[$key]['pf'] = $emp->pf;
            $salary = $emp->basic+$emp->hr+$emp->da+$emp->conveyance;
            $deduction = $emp->esi+$emp->pf;
            $employee[$key]['toatl_salary'] = $salary - $deduction;
            $employee[$key]['paid_date'] = '-';
            $employee[$key]['status'] = '-';
        }
        
        $data['employee_salary'] = $employee;
        return view('admin.employees.salary_list',$data);
    }
}
