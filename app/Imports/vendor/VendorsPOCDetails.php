<?php

namespace App\Imports\vendor;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\VendorPoc;
class VendorsPOCDetails implements ToCollection , WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    use Importable;
    public function collection(Collection $rows)
    {
       foreach ($rows as $key => $row) {
       	$data=[

       		'vendor_id'		=> $row['vendorid'],
       		'name'			=> $row['name'],
       		'email'			=> $row['email'],
       		'contact_no'	=> $row['contact_no'],

       	];
       		VendorPoc::insert($data);
       }
    }
}
