<?php

namespace App\Exports\customer;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\User;
use Maatwebsite\Excel\Concerns\WithTitle;
class CustomerCompanyDetails implements FromView,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	$data=array();
        $data['all_customerss']=User::with('getParentConpany','address','getCountry','getState','getCity','getSalesRep')->where('role_id',7)->where('is_deleted',0)->where('appoved_status',3)->orderBy('users.id','ASC')->get();
        return view('admin.exports.customer.customer_company_details',$data);
    }
    public function title(): string
    {
        return 'CompanyDetails';
    }
}
