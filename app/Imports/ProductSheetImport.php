<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Brand;
use App\Models\Categories;
use App\Models\Product;
class ProductSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {

    		
    		// dd($rows);

    	foreach ($rows as $key => $row) {
    	
    	$category=Categories::where('name','like','%'.$row['categoryname'].'%')
    			  ->where('published',1)
    			  ->where('is_deleted',0)
    			  ->first();

    		$brand_id=0;
    		if ($row['brandname']!='') {
    			$brand=Brand::firstorCreate(['name' =>$row['brandname']]);
    			$brand_id=$brand->id;
    		}
    		$data[]=[
    			'id'	=> $row['productid'],
    			'name'	=> $row['productname'],
    			'code'	=>'test',
    			'sku'	=> isset($row['sku'])?$row['sku']:'',
    			'category_id'	=>$category->id,
    			'brand_id'		=> $brand_id,
    			'main_image'	=> '',
    			'short_description' => isset($row['productshortdetails'])?$row['productshortdetails']:'',
    			'long_description' => isset($row['productshortdetails'])?$row['productshortdetails']:'',
    			'treatment_information' => isset($row['treatmentinformation'])?$row['treatmentinformation']:'',
    			'dosage_instructions' => isset($row['dosageinstructions'])?$row['dosageinstructions']:'',
    			'alert_quantity' => $row['productname'],
    			'commission_type' => ($row['']=='Percentage')?0:1,
    			'commission_value' => isset($row['commissionvalue'])?$row['commissionvalue']:'',
    			'published' => ($row['published']=='yes')?1:0,
    			'show_home'		=> ($row['showhomepage']=="yes")?1:0,
    			'search_engine_name' =>'',
    			'meta_title'	=> $row['metatitle'],
    			'meta_keyword'	=> $row['metakeywords'],
    			'meta_description'	=> $row['metadescription'],
    			'created_at'	=> date('Y-m-d H:i:s'),
    			'is_deleted'	=> 0,
    		];
    		   
    		
    		// Product::firstorCreate(['name'=>$row['productname']],$data);
    	}
    	dd($data);

    	/*$count=1;
    	foreach ($rows as $key => $row) {
    		if ($row['brandname']!='') {
    			$brand=Brand::firstorCreate(['name' =>$key]);
    		}
    		else{
    			$brand=0;	
    		}

    		$check_brand[]=$brand;    		
    		$count++;
    	}	
    	dd(Brand::all());*/
    	
    }
}
