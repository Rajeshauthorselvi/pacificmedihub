<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\UserBankAcccount;
use App\User;
use Illuminate\Validation\Rule;
class CustomerBankDetails implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {

        foreach ($rows as $key => $row) {
        	$data=[
        			'customer_id'		=> $row['customerid'],
        			'account_name'		=> $row['account_name'],
        			'account_number'	=> $row['account_number'],
        			'bank_name'			=> $row['bank_name'],
        			'bank_branch'		=> $row['bank_branch'],
        			'ifsc_code'			=> $row['ifsc_code'],
        			'paynow_contact'	=> $row['paynow_contact'],
        			'place'				=> $row['place'],
        			'others'			=> $row['others']
        		];
        		$bank_account_id=UserBankAcccount::insertGetId($data);

        	User::where('id',$row['customerid'])->update(['bank_account_id'=>$bank_account_id]);

        }
    }
    public function rules(): array
    {

    	$rules=
    		[
    			'customerid'	=>'required'
    	    ];

    	return $rules;
    }
}
