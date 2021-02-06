<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Brand;
class ProductSheetImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {
    	$count=1;
    	foreach ($rows as $key => $row) {
    		if ($row['brandname']!='') {
    			var_dump($row['brandname']);
    			$brand=Brand::insertGetId(['name' =>$row['brandname']]);
    		}
    		else{
    			$brand=0;	
    		}

    		$check_brand[]=$brand;    		
    		$count++;
    	}	
    	dd(Brand::all());
    	
    }
}
