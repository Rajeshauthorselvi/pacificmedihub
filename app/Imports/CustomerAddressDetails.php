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
use App\Models\UserAddress;
use App\User;
use Illuminate\Validation\Rule;
class CustomerAddressDetails implements ToCollection, WithHeadingRow, WithValidation
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
        		'name'				=> $row['name'],	
        		'mobile'			=> $row['contact_no'],
        		'address_line1'		=> $row['address_line1'],
        		'address_line2'		=> $row['address_line1'],
        		'post_code'			=> $row['post_code'],
        		'country_id'		=> $this->CountryId($row['country']),
        		'state_id'			=> $this->StateId($row['state']),
        		'city_id'			=> $this->CityId($row['city']),
        		'latitude'			=> $row['latitude'],
        		'longitude'			=> $row['longitude'],
        		'address_type'		=> ($row['is_default']=="Yes")?1:0,
        		'created_at'		=> date('Y-m-d H:i:s'),
        		'is_deleted'		=> 0,
        	];
        	$address_id=UserAddress::insertGetId($data);
        	User::where('id',$row['customerid'])->update(['address_id'=>$address_id]);
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

    public function rules(): array
    {

    	$rules=
    		[
    			'customerid'	=>'required'
    	    ];

    	return $rules;
    }
}
