<?php

use Illuminate\Database\Seeder;
use App\Models\Tax;
class Taxes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
    		['name'=>'No Tax','code'=>'no-tax','tax_type'=>'p','rate'=>0,'published'=>1,'created_at'=>date('Y-m-d H:i:s')]
    	];
        Tax::insert($data);
    }
}
