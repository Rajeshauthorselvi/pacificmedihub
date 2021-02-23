<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Countries;
use App\Models\State;
use App\Models\City;
use App\Models\Prefix;
use App\Models\EmpSalaryHistory;
use App\Models\EmpSalaryStatus;
use App\Models\CommissionValue;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\OrderStatus;
use App\Models\Product;
use Carbon\Carbon;
use Session;
use Redirect;
use Arr;
use DB;

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

        //Commission Id 1 is a Base Commission
        $data['base_commissions']     = CommissionValue::where('commission_id',1)->where('published',1)
                                            ->where('is_deleted',0)->get();
        //Commission Id 3 is a Target Commission
        $data['target_commissions']   = CommissionValue::where('commission_id',3)->where('published',1)
                                            ->where('is_deleted',0)->get();                                          

        $employee_code = Prefix::where('key','prefix')->where('code','employee')->value('content');
        if (isset($employee_code)) {
            $value = unserialize($employee_code);
            $char_val = $value['value'];
            $year = date('Y');
            $total_datas = Employee::count();
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
            $data['employee_id']=$replace_number;
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
            'country_id'     => 'required',
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

        $role = Department::where('id',$request->dept_id)->first();
        
        $add_emp = new Employee;
        $add_emp->emp_id = $request->emp_id;
        $add_emp->role_id = $role->role_id;
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
        // $add_emp->emp_ifsc_code = $request->ifsc;
        $add_emp->emp_paynow_contact_number = $request->paynow_no;
        $add_emp->basic = $request->basic;
        $add_emp->self_cpf = $request->cpf_self;
        $add_emp->emp_cpf = $request->cpf_emp;
        $add_emp->sdl = $request->sdl;
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
            $salary_history->self_cpf = $request->cpf_self;
            $salary_history->emp_cpf = $request->cpf_emp;
            $salary_history->sdl = $request->sdl;
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
        //Commission Id 1 is a Base Commission
        $data['base_commissions']     = CommissionValue::where('commission_id',1)->where('published',1)
                                            ->where('is_deleted',0)->get();
        //Commission Id 3 is a Target Commission
        $data['target_commissions']   = CommissionValue::where('commission_id',3)->where('published',1)
                                            ->where('is_deleted',0)->get();

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
        //Commission Id 1 is a Base Commission
        $data['base_commissions']     = CommissionValue::where('commission_id',1)->where('published',1)
                                            ->where('is_deleted',0)->get();
        //Commission Id 3 is a Target Commission
        $data['target_commissions']   = CommissionValue::where('commission_id',3)->where('published',1)
                                            ->where('is_deleted',0)->get();
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
            'country_id'     => 'required',
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
        /*$update_emp->emp_ifsc_code = $request->ifsc;*/
        $update_emp->emp_paynow_contact_number = $request->paynow_no;
        $update_emp->basic = $request->basic;
        $update_emp->self_cpf = $request->cpf_self;
        $update_emp->emp_cpf = $request->cpf_emp;
        $update_emp->sdl = $request->sdl;
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
                $salary_history->self_cpf = $request->cpf_self;
                $salary_history->emp_cpf = $request->cpf_emp;
                $salary_history->sdl = $request->sdl;
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
        $data     = array();
        $employee = array();

        $get_employees = Employee::where('status',1)->where('is_deleted',0)->get();
        $pre_month     = Carbon::now()->subMonth()->format('m');
         
        foreach ($get_employees as $key => $emp) {
            $salaryStatus   = EmpSalaryStatus::where('emp_id',$emp->id)->whereRaw('MONTH(paid_date)=?',date('m'))
                                ->first();
            $get_product_id = DB::table('orders as o')
                                ->where('o.sales_rep_id',$emp->id)
                                ->where('o.order_status',13)
                                ->whereRaw('MONTH(o.order_completed_at)=?',$pre_month)
                                ->leftJoin('order_products as op','o.id','=','op.order_id')
                                ->pluck('op.product_id')->toArray();

            $product_id = array_unique($get_product_id);
            $product_commission = 0;
            foreach($product_id as $id){
                $product = Product::find($id);
                if($product->commissionType->commission_type=='p'){
                    $order_per        = OrderProducts::where('product_id',$id)->sum('final_price');
                    $percentage_value = ($product->commission_value/100)*$order_per;
                }else{
                    $fixed_value      = $product->commission_value;
                }
                $product_commission = (isset($percentage_value)?$percentage_value:0)+(isset($fixed_value)?$fixed_value:0);
            }

            if($emp->baseCommission->commission_type=='f'){
                $commission = $product_commission*$emp->basic_commission_value;
            }else{
                $commission = $product_commission*($emp->basic_commission_value/100);
            }
            
            $targetCommissions  = Orders::where('sales_rep_id',$emp->id)->where('order_status',13)
                                        ->whereRaw('MONTH(order_completed_at)=?',$pre_month)->sum('total_amount');
            $t_commission       = (float)$targetCommissions;
            $target_commissions = 0;

            if($t_commission>=$emp->target_value){
                if($emp->targetCommission->commission_type=='f'){
                    $target_commissions = $emp->target_commission_value;
                }else{
                    $target_commissions = $emp->target_value*($emp->target_commission_value/100);
                }
            }

            $employee[$key]['id']         = $emp->id;
            $employee[$key]['name']       = $emp->emp_name;
            $employee[$key]['department'] = $emp->department->dept_name;
            $payment                      = $emp->basic + $commission + $target_commissions;
            $deduction                    = $emp->self_cpf + $emp->sdl;
            $employee[$key]['payment']    = $payment;
            $employee[$key]['deduction']  = $deduction;

            $total_salary = $payment - $deduction;

            if($salaryStatus){
                $paid_date = date('d/m/Y',strtotime($salaryStatus->paid_date));
                if($salaryStatus->status==1) {
                    $status = 'Paid'; 
                    $action = 'Payslip';
                    $total_salary = $salaryStatus->paid_amount;
                }
                else{
                    $status = 'Not Paid';
                    $action = 'Paynow';
                }
            }else{
                $paid_date = '-';
                $status = 'Not Paid';
                $action = 'Paynow';
            }
            $employee[$key]['total_salary'] = $total_salary;
            $employee[$key]['paid_date']    = $paid_date;
            $employee[$key]['status']       = $status;
            $employee[$key]['action']       = $action;
        }
        $data['employee_salary'] = $employee;

        return view('admin.employees.salary_list',$data);
    }

    public function salaryView($emp_id)
    {
        $id = base64_decode($emp_id);
        $employee = Employee::find($id);
        $data['employee'] = $employee;
        $pre_month     = Carbon::now()->subMonth()->format('m');
        $salaryStatus     = EmpSalaryStatus::where('emp_id',$id)->whereRaw('MONTH(paid_date)=?',date('m'))
                                ->first();
        if($salaryStatus){
            $data['paid_date'] = date('d/m/Y',strtotime($salaryStatus->paid_date));
        }else{
            $data['paid_date'] = 'Not Paid';
        }

        $get_product_id = DB::table('orders as o')->where('o.sales_rep_id',$id)->where('o.order_status',13)
                                ->whereRaw('MONTH(o.order_completed_at)=?',$pre_month)
                                ->leftJoin('order_products as op','o.id','=','op.order_id')
                                ->pluck('op.product_id')->toArray();

        $product_id = array_unique($get_product_id);
        $product_commission = 0;
        foreach($product_id as $id){
            $product = Product::find($id);
            if($product->commissionType->commission_type=='p'){
                $order_per        = OrderProducts::where('product_id',$id)->sum('final_price');
                $percentage_value = ($product->commission_value/100)*$order_per;
            }else{
                $fixed_value      = $product->commission_value;
            }
            $product_commission = (isset($percentage_value)?$percentage_value:0)+(isset($fixed_value)?$fixed_value:0);
        }

        if($employee->baseCommission->commission_type=='f'){
            $commission = $product_commission*$employee->basic_commission_value;
        }else{
            $commission = $product_commission*($employee->basic_commission_value/100);
        }
          
        $targetCommissions  = Orders::where('sales_rep_id',$employee->id)->where('order_status',13)
                                    ->whereRaw('MONTH(order_completed_at)=?',$pre_month)->sum('total_amount');
        $t_commission       = (float)$targetCommissions;
        $target_commissions = 0;

        if($t_commission>=$employee->target_value){
            if($employee->targetCommission->commission_type=='f'){
                $target_commissions = $employee->target_commission_value;
            }else{
                $target_commissions = $employee->target_value*($employee->target_commission_value/100);
            }
        }

        $data['base_salary']        = isset($employee->basic)?$employee->basic:'0.00';
        $data['commission']         = isset($commission)?$commission:'0.00';
        $data['target_commissions'] = isset($target_commissions)?$target_commissions:'0.00';
        $data['cpf']                = isset($employee->cpf_self)?$employee->cpf_self:'0.00';
        $data['sdl']                = isset($employee->sdl)?$employee->sdl:'0.00';
        $data['employer_cpf']       = isset($employee->employer_cpf)?$employee->employer_cpf:'0.00';

        $payment_total              = $employee->basic + $commission + $target_commissions;
        $deduction_total            = $employee->self_cpf + $employee->sdl;
        $data['payment_total']      = $payment_total;
        $data['deduction_total']    = $deduction_total;    
        $data['new_salary']         = $payment_total - $deduction_total;

        return view('admin.employees.view_salary',$data);
    }


    public function monthSalaryList(Request $request)
    {
        dd($request->all());
        
        $data     = array();
        $employee = array();

        $get_employees = Employee::where('status',1)->where('is_deleted',0)->get();
        $pre_month     = Carbon::now()->subMonth()->format('m');
         
        foreach ($get_employees as $key => $emp) {
            $salaryStatus   = EmpSalaryStatus::where('emp_id',$emp->id)->whereRaw('MONTH(paid_date)=?',date('m'))
                                ->first();
            $get_product_id = DB::table('orders as o')
                                ->where('o.sales_rep_id',$emp->id)
                                ->where('o.order_status',13)
                                ->whereRaw('MONTH(o.order_completed_at)=?',$pre_month)
                                ->leftJoin('order_products as op','o.id','=','op.order_id')
                                ->pluck('op.product_id')->toArray();

            $product_id = array_unique($get_product_id);
            $product_commission = 0;
            foreach($product_id as $id){
                $product = Product::find($id);
                if($product->commissionType->commission_type=='p'){
                    $order_per        = OrderProducts::where('product_id',$id)->sum('final_price');
                    $percentage_value = ($product->commission_value/100)*$order_per;
                }else{
                    $fixed_value      = $product->commission_value;
                }
                $product_commission = (isset($percentage_value)?$percentage_value:0)+(isset($fixed_value)?$fixed_value:0);
            }

            if($emp->baseCommission->commission_type=='f'){
                $commission = $product_commission*$emp->basic_commission_value;
            }else{
                $commission = $product_commission*($emp->basic_commission_value/100);
            }
            
            $targetCommissions  = Orders::where('sales_rep_id',$emp->id)->where('order_status',13)
                                        ->whereRaw('MONTH(order_completed_at)=?',$pre_month)->sum('total_amount');
            $t_commission       = (float)$targetCommissions;
            $target_commissions = 0;

            if($t_commission>=$emp->target_value){
                if($emp->targetCommission->commission_type=='f'){
                    $target_commissions = $emp->target_commission_value;
                }else{
                    $target_commissions = $emp->target_value*($emp->target_commission_value/100);
                }
            }

            $employee[$key]['id']         = $emp->id;
            $employee[$key]['name']       = $emp->emp_name;
            $employee[$key]['department'] = $emp->department->dept_name;
            $payment                      = $emp->basic + $commission + $target_commissions;
            $deduction                    = $emp->self_cpf + $emp->sdl;
            $employee[$key]['payment']    = $payment;
            $employee[$key]['deduction']  = $deduction;

            $total_salary = $payment - $deduction;

            if($salaryStatus){
                $paid_date = date('d/m/Y',strtotime($salaryStatus->paid_date));
                if($salaryStatus->status==1) {
                    $status = 'Paid'; 
                    $action = 'Payslip';
                    $total_salary = $salaryStatus->paid_amount;
                }
                else{
                    $status = 'Not Paid';
                    $action = 'Paynow';
                }
            }else{
                $paid_date = '-';
                $status = 'Not Paid';
                $action = 'Paynow';
            }
            $employee[$key]['total_salary'] = $total_salary;
            $employee[$key]['paid_date']    = $paid_date;
            $employee[$key]['status']       = $status;
            $employee[$key]['action']       = $action;
        }
        $data['employee_salary'] = $employee;

        return view('admin.employees.salary_list',$data);
    }

    public function paymentForm(Request $request)
    {
        $emp = Employee::find($request->emp_id);
        $pre_month      = Carbon::now()->subMonth()->format('m');
        $get_product_id = DB::table('orders as o')->where('o.sales_rep_id',$emp->id)
                                ->where('o.order_status',13)->whereRaw('MONTH(o.order_completed_at)=?',$pre_month)
                                ->leftJoin('order_products as op','o.id','=','op.order_id')
                                ->pluck('op.product_id')->toArray();

        $product_id = array_unique($get_product_id);
        $product_commission = 0;
        foreach($product_id as $id){
            $product = Product::find($id);
            if($product->commissionType->commission_type=='p'){
                $order_per        = OrderProducts::where('product_id',$id)->sum('final_price');
                $percentage_value = ($product->commission_value/100)*$order_per;
            }else{
                $fixed_value      = $product->commission_value;
            }
            $product_commission = (isset($percentage_value)?$percentage_value:0)+(isset($fixed_value)?$fixed_value:0);
        }

        if($emp->baseCommission->commission_type=='f'){
            $commission = $product_commission*$emp->basic_commission_value;
        }else{
            $commission = $product_commission*($emp->basic_commission_value/100);
        }
        
        $targetCommissions  = Orders::where('sales_rep_id',$emp->id)->where('order_status',13)
                                    ->whereRaw('MONTH(order_completed_at)=?',$pre_month)->sum('total_amount');
        $t_commission       = (float)$targetCommissions;
        $target_commissions = 0;

        if($t_commission>=$emp->target_value){
            if($emp->targetCommission->commission_type=='f'){
                $target_commissions = $emp->target_commission_value;
            }else{
                $target_commissions = $emp->target_value*($emp->target_commission_value/100);
            }
        }
        $data['id']           = $emp->id;
        $data['name']         = $emp->emp_name;
        $data['department']   = $emp->department->dept_name;
        $payment              = $emp->basic + $commission + $target_commissions;
        $deduction            = $emp->self_cpf + $emp->sdl;
        $total_salary         = $payment - $deduction;
        $data['total_salary'] = number_format($total_salary,2,'.','');
        $data['salary_month'] = date('F Y');
        return view('admin.employees.payment_form',$data);
    }

    public function confirmSalary(Request $request)
    {
        $salary              = new EmpSalaryStatus;
        $salary->emp_id      = $request->emp_id;
        $salary->paid_amount = $request->total_salary;
        $salary->paid_date   = date('Y-m-d');
        $salary->status      = 1;
        $salary->created_at  = date('Y-m-d H:i:s');
        $salary->save();

        return Redirect::route('salary.list')->with('success','Paid Successfully.!');
    }
}
