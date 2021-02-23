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
use App\Models\Prefix;
class ProductSheetImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {
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

            $code= '';
            $product_code = Prefix::where('key','prefix')->where('code','product_code')->value('content');
            if (isset($product_code)) {
                $value = unserialize($product_code);
                $char_val = $value['value'];
                $year = date('Y');
                $total_datas = Product::count();
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
                $code=$replace_number;
            }
            $product_slug=strtolower(str_replace(' ','-',$row['productname']));
    		$data=[
    			'id'	=> $row['productid'],
    			'name'	=> $row['productname'],
    			'code'	=> $code,
    			'sku'	=> isset($row['sku'])?$row['sku']:'',
    			'category_id'	=> $category->id,
    			'brand_id'		=> $brand_id,
    			'main_image'	=> '',
    			'short_description' => isset($row['productshortdetails'])?$row['productshortdetails']:'',
    			'long_description' => isset($row['productshortdetails'])?$row['productshortdetails']:'',
    			'treatment_information' => isset($row['treatmentinformation'])?$row['treatmentinformation']:'',
    			'dosage_instructions' => isset($row['dosageinstructions'])?$row['dosageinstructions']:'',
    			'alert_quantity' => $row['alertquantity'],
    			'commission_type' => ($row['commissiontype']=='Percentage')?0:1,
    			'commission_value' => isset($row['commissionvalue'])?$row['commissionvalue']:'',
    			'published' => ($row['published']=='yes')?1:0,
    			'show_home'		=> ($row['showhomepage']=="yes")?1:0,
    			'search_engine_name' =>$product_slug,
    			'meta_title'	=> $row['metatitle'],
    			'meta_keyword'	=> $row['metakeywords'],
    			'meta_description'	=> $row['metadescription'],
    			'created_at'	=> date('Y-m-d H:i:s'),
    			'is_deleted'	=> 0,
    		];
    		Product::insert($data);
    	}
    	
    }
    public function rules(): array
    {
        return [
            // Above is alias for as it always validates in batches
            'productid'=>'required|unique:products,id',
            'categoryname'=>'required|exists:categories,name'
        ];
    }
}
