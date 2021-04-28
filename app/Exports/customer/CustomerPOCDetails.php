<?php

namespace App\Exports\customer;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\UserPoc;
use App\User;
use Maatwebsite\Excel\Concerns\WithTitle;
class CustomerPOCDetails implements FromView,WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
		$data['all_poc']=User::with('CustomerPoc')
							   ->where('role_id',7)
							   ->where('is_deleted',0)
							   ->where('appoved_status',3)
							   ->orderBy('users.id','ASC')
							   ->get();

							   // dd($data);

		return view('admin.exports.customer.customer_poc',$data);
    }
    public function title(): string
    {
        return 'CustomerPOC';
    }
}
