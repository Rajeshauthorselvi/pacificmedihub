<?php

use Illuminate\Database\Seeder;
use App\Models\PaymentTerm;
class PaymentTerms extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
    		['name'=>'30 day credit','published'=>1,'created_at'=>date('Y-m-d H:i:s')],
    		['name'=>'60 day credit','published'=>1,'created_at'=>date('Y-m-d H:i:s')],
    		['name'=>'90 day credit','published'=>1,'created_at'=>date('Y-m-d H:i:s')],
    		['name'=>'Cash on delivery','published'=>1,'created_at'=>date('Y-m-d H:i:s')]
    	];
        PaymentTerm::insert($data);
    }
}
