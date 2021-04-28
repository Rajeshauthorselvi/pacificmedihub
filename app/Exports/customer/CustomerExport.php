<?php

namespace App\Exports\customer;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class CustomerExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    public function sheets(): array
    {
        return [
        	'CompanyDetails'				=> new CustomerCompanyDetails(),
        	'CustomerPOC'					=> new CustomerPOCDetails(),
        	'Address'						=> new CustomerAddressDetails(),
        	'CustomerBankDetails'			=> new CustomerBankDetails()
        ];
    }
}
