<?php

use Illuminate\Database\Seeder;
use App\Models\Department;
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
    	['dept_id'=>'#0001','dept_name'=>'Sales Rep','status'=>1,'is_deleted'=>0,'created_at'=>date('Y-m-d H:i:s')],
    	['dept_id'=>'#0002','dept_name'=>'Logistics','status'=>1,'is_deleted'=>0,'created_at'=>date('Y-m-d H:i:s')],
    	['dept_id'=>'#0003','dept_name'=>'Delivery Person','status'=>1,'is_deleted'=>0,'created_at'=>date('Y-m-d H:i:s')],
    	['dept_id'=>'#0004','dept_name'=>'Admin','status'=>1,'is_deleted'=>0,'created_at'=>date('Y-m-d H:i:s')],
    	];
        Department::insert($data);
    }
}
