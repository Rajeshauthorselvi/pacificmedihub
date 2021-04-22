<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Countries;
use App\Models\State;
use App\Models\Employee;
use App\Models\Prefix;
use App\User;
use Illuminate\Validation\Rule;
use App\Mail\NewRegister;
use Hash;
use Mail;
use Str;
class CustomerCompanyDetails implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows) 
    {

    	
        foreach ($rows as $key => $row) {
        		$customer=[
                    'id'                => $row['customerid'],
        			'role_id'			=> 7,
                    'customer_no'       => $this->CustomerCode(),
        			'name'				=> $row['company_name'],
                    'parent_company'    => $this->ParentCompany($row['parent_company_name']),
        			'company_uen'		=> $row['company_uen'],
        			'email'				=> $row['login_email'],
        			'contact_number'	=> $row['contact_no'],
        			'address_1'			=> $row['address_line1'],
        			'address_2'			=> $row['address_line2'],
        			'country_id'		=> $this->CountryId($row['country']),
        			'state_id'			=> $this->StateId($row['state']),
        			'city_id'			=> $this->CityId($row['city']),
        			'post_code'			=> $row['post_code'],
                    'sales_rep'         => $this->SalesRep($row['sales_rep']),
        			'request_from'		=> 1,
        			'appoved_status'	=> 3,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'status'            => ($row['published']=="Yes")?1:0,
                    'is_deleted'        => 0
        		];

                $check_exists=User::where('email',$row['login_email'])->first();
                if ($check_exists) {
                    User::where('email',$row['login_email'])->update($customer);
                }
                else{
                    $customerid=User::insertGetId($customer);
                    if ($row['published']=="Yes") {
                        $this->TriggerEmail($customerid);
                    }
                }
        }

        // dd($customer);
    }
    public function TriggerEmail($customerid='')
    {
            $password = Str::random(6);
            $customer = User::find($customerid);
            $customer->password = Hash::make($password);
            $customer->mail_sent_status = 1;
            $customer->save();
            Mail::to($customer->email)->send(new NewRegister($customer->name, $customer->email,$password));
    }
    public function SalesRep($sales_rep='')
    {
    	return Employee::where('emp_department',1)->where('emp_name',$sales_rep)->value('id');
    }

    public function ParentCompany($parent_company='')
    {
        if ($parent_company!="" || $parent_company!=null) {
            $parent=User::where('name',$parent_company)->value('id');
            if (isset($parent)) {
                return $parent;
            }
            else{
                return 0;
            }
        }
        else{ 
            return 0;
        }
    }

    public function CountryId($country)
    {
    	return Countries::where('name',$country)->value('id');
    }

    public function CityId($city="")
    {
    	if ($city!="" || $city!=null) {
	    	return City::where('name',$city)->value('id');
    	}
    	else{
    		return 0;
    	}
    }

    public function StateId($state="")
    {
    	if ($state!="" || $state!=null) {
	    	return State::where('name',$state)->value('id');
    	}
    	else{
    		return 0;
    	}
    }

    public function CustomerCode()
    {
        $customer_unique= '';
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
            $customer_unique = $replace_number;
        }

        return $customer_unique;
    }

    public function rules(): array
    {

        $rules=
            [
                'customerid'    =>'required|unique:users,id',
                // 'login_email'   => 'required|unique:users,email',
                'sales_rep'     => 'required',
                'published'     =>[
                    'required', Rule::in(['Yes', 'No'])
                ],
                'login_email' => Rule::unique('users','email'),
            ];

        return $rules;
    }

}
