<?php

namespace App\Exports\customer;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\User;
use App\Models\UserBankAcccount;
use Maatwebsite\Excel\Concerns\WithTitle;
class CustomerBankDetails implements FromView,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
		$all_customers_ids=User::where('role_id',7)
							   ->where('is_deleted',0)
							   ->where('appoved_status',3)
							   ->orderBy('users.id','ASC')
							   ->pluck('id')->toArray();
		$data['all_bank_details']=UserBankAcccount::whereIn('customer_id',$all_customers_ids)->get();

		return view('admin.exports.customer.customer_bank_details',$data);
    }
    public function title(): string
    {
        return 'CustomerBankDetails';
    }

}
