<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Vendor;
use Illuminate\Validation\Rule;
use App\Models\ProductVariant;
use App\Models\ProductVariantVendor;
use App\Models\Option;
use App\Models\OptionValue;

class ProductVariationsSheetImport implements ToCollection, WithValidation, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {



    	$data=array();
        foreach ($rows as $key => $row) {

        	$vendor_details=Vendor::where('name','like','%'.$row['vendorname'].'%')->first();
        	if (isset($row['productid'])) {
        		$option_1_id=$this->OptionId($row['option1']);
        		$option_2_id=$this->OptionId($row['option2']);
        		$option_3_id=$this->OptionId($row['option3']);
        		$option_4_id=$this->OptionId($row['option4']);
        		$option_5_id=$this->OptionId($row['option5']);
        	
        		$option_1_value_id=$this->OptionValueId($option_1_id,$row['optionvalue1']);
        		$option_2_value_id=$this->OptionValueId($option_2_id,$row['optionvalue2']);
        		$option_3_value_id=$this->OptionValueId($option_3_id,$row['optionvalue3']);
        		$option_4_value_id=$this->OptionValueId($option_4_id,$row['optionvalue4']);
        		$option_5_value_id=$this->OptionValueId($option_5_id,$row['optionvalue5']);

        		$variant_details=[
        			'product_id'		=> $row['productid'],
        			'option_id'			=> $option_1_id,
        			'option_value_id'	=> $option_1_value_id,
        			'option_id2'		=> $option_2_id,
        			'option_value_id2'	=> $option_2_value_id,
        			'option_id3'		=> $option_3_id,
        			'option_value_id3'	=> $option_3_value_id,
        			'option_id4'		=> $option_4_id,
        			'option_value_id4'	=> $option_4_value_id,
        			'option_id5'		=> $option_5_id,
        			'option_value_id5'	=> $option_5_value_id,
        			'is_deleted'		=> 0,
        			'created_at'		=> date('Y-m-d H:i:s'),
        			'disabled'			=> 0
        		];
	        	$variant_id=ProductVariant::insertGetId($variant_details);
	        	$variant_vendor_details=[
	        		'product_id' 			=> $row['productid'],
	        		'product_variant_id' 	=> $variant_id,
	        		'base_price'			=> $row['baseprice'],
	        		'retail_price'			=> $row['retailprice'],
	        		'minimum_selling_price'	=> $row['minimumsellingprice'],
	        		'display_variant'		=> ($row['display']=="yes")?1:0,
	        		'display_order'			=> $row['orderby'],
	        		'stock_quantity'		=> $row['stockqty'],
	        		'vendor_id'				=> $vendor_details->id
	        	];
	        	ProductVariantVendor::insert($variant_vendor_details);
        	}
        }
    }

    public function OptionId($option)
    {
    	$check_options=Option::where('option_name',$option)->first();
    	if($check_options){
    		$option_id=$check_options->id;
    	}
    	else{
    		if ($option!="") {
	    		$option_id=Option::insertGetId(
	    			[
	    				'option_name'=>$option,
	    				'published'	 => 'yes',
	    				'created_at' => date('Y-m-d H:i:s'),
	    				'is_deleted' => 0,
	    			]
	    		);	
    		}
    		else{
    			$option_id='';
    		}
    	}
    	
    	return $option_id;
    }
    public function OptionValueId($option_id,$option_value)
    {
    	$option_value_exists=OptionValue::where('option_value',$option_value)
    						 ->where('option_id',$option_id)
    						 ->first();

    	if ($option_value_exists) {
	    	$option_value_id=$option_value_exists->id;
    	}
    	else{
    		if ($option_value!="") {
	    		$option_value_id=OptionValue::insertGetId(
	    			[
	    				'option_id'			=> $option_id,
	    				'option_value'	 	=> $option_value,
	    				'display_order'		=> 0,
	    				'created_at'		=> date('Y-m-d H:i:s'),
	    				'is_deleted'		=> 0
	    			]
	    		);
    		}
    		else{
    			$option_value_id="";
    		}
    	}

    	return $option_value_id;
    }
    public function rules(): array
    {
        return [
            // Above is alias for as it always validates in batches
            'vendorname'=>'required|exists:vendors,name'
        ];
    }

}
