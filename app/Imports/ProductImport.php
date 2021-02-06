<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
 use App\Models\Brand;
class ProductImport implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
    		
        return [
            'ProductDetails' => new ProductSheetImport()
            // 'ProductVariants' => new ProductVariationsSheetImport(),
        ];
    }
}
