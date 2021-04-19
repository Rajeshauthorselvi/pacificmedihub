<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Imports\CustomerCompanyDetails;
use App\Imports\CustomerPocDetails;

class CustomerImport implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
    public function sheets(): array
    {
        return [
        	 new CustomerCompanyDetails(),
        	 new CustomerPocDetails(),
        	 new CustomerAddressDetails(),
        	 new CustomerBankDetails(),
        ];
    }
}
