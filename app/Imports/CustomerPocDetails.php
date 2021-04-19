<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\UserPoc;
class CustomerPocDetails implements ToCollection, WithValidation, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {
        $data=array();

        foreach ($rows as $key => $row) {
        	$data[]=[
        		'customer_id'		=> $row['customerid'],
        		'name'				=> $row['name'],
        		'email'				=> $row['email'],
        		'contact_number'	=> $row['phone_no']
        	];
        	UserPoc::insert($data);
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
