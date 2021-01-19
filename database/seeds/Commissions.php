<?php

use Illuminate\Database\Seeder;
use App\Models\Commissions as Commission;
class Commissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$data=[
    		['commission_name'=>'Base Commission','status'=>1,'created_at'=>date('Y-m-d H:i:s')],
    		['commission_name'=>'Product Commission','status'=>1,'created_at'=>date('Y-m-d H:i:s')],
    		['commission_name'=>'Target Commission','status'=>1,'created_at'=>date('Y-m-d H:i:s')]
    	];
        Commission::insert($data);
    }
}
