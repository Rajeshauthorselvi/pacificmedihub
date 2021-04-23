<?php

namespace App\Imports\vendor;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Countries;
use App\Models\State;
use App\Models\Vendor;
use App\Models\Prefix;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
class VendorsGeneralDetails implements  ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {
    	
        foreach ($rows as $key => $row) {
        	$data=[
        		'id'				=> $row['vendorid'],
        		'code'				=> $this->VendorCode(),
        		'uen'				=> $row['vendor_uen'],
        		'name'				=> $row['vendor_name'],
        		'email'				=> $row['email'],
        		'contact_number'	=> $row['contact_no'],
        		'logo_image'		=> $row['vendor_logo'],
        		'gst_no'			=> $row['vendor_gst'],
        		'gst_image'			=> $row['gst_certificate'],
        		'address_line1' 	=> $row['address'],
        		'website'			=> $row['website'],
        		'post_code'			=> $row['postcode'],
        		'country' 			=> $this->CountryId($row['country']),
        		'state'				=> $this->StateId($row['state']),
        		'city' 				=> $this->StateId($row['city']),
        		'account_name'		=> $row['account_name'],
        		'account_number'	=> $row['account_number'],
        		'bank_name'			=> $row['bank_name'],
        		'bank_branch'		=> $row['bank_branch'],
        		'paynow_contact_number'	=> $row['paynow_contact_no'],
        		'bank_place'			=> $row['bank_place'],
        		'others'				=> $row['others'],
        		'created_at'			=> date('Y-m-d H:i:s'),
        		'status'				=> ($row['published']=="Yes" || $row['published']=="yes")?1:0,
        		'is_deleted'			=> 0
        	];
        	$check_vendor_exists=Vendor::where('email',$row['email'])->first();
        	if ($check_vendor_exists) {
        		Vendor::where('email',$row['email'])->update($data);
        	}
        	else{
	        	Vendor::insert($data);
        	}
        }
    }
    public function VendorCode()
    {
		 	$vendor_id      = '';
		        $vendor_code = Prefix::where('key','prefix')->where('code','vendor')->value('content');
		        if (isset($vendor_code)) {
		            $value = unserialize($vendor_code);
		            $char_val = $value['value'];
		            $year = date('Y');
		            $total_datas = Vendor::count();
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
		            $vendor_id=$replace_number;
		        }


		        return $vendor_id;
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
    	return [
    		'vendorid' => 'required|unique:vendors,id',
    		'email' => 'required|unique:vendors',
    		'vendor_uen'	 => 'required',
			'vendor_name' => 'required',
			'contact_no' => 'required',
			'address' => 'required',
			'country' => 'required',
    	];
    }
}
