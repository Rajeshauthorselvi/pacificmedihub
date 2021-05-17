<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orders;
use App\Models\OrderProducts;
use App\Models\Product;
use DB;

class Employee extends Model
{
    public function department()
    {
    	return $this->belongsTo('App\Models\Department','emp_department');
    }
    public function role()
    {
        return $this->belongsTo('App\Models\Role','role_id');
    }

    public function country()
    {
    	return $this->belongsTo('App\Models\Countries','emp_country');
    }

    public function state()
    {
    	return $this->belongsTo('App\Models\State','emp_state');
    }

    public function city()
    {
    	return $this->belongsTo('App\Models\City','emp_city');
    }

    public function baseCommission(){
        return $this->belongsTo('App\Models\CommissionValue','basic_commission_type');
    }

    public function targetCommission(){
        return $this->belongsTo('App\Models\CommissionValue','target_commission_type');
    }

    static function getCommissionValue($emp_id,$pre_month,$year){

        $orders = DB::table('orders')->where('sales_rep_id',$emp_id)->where('order_status',13)
                                     ->whereMonth('order_completed_at',$pre_month)
                                     ->whereYear('order_completed_at',$year)->get();

        $p_commission = 0; $commission=0; $commissions = 0;
        foreach ($orders as $key => $order) {

            $order_prd_id = OrderProducts::where('order_id',$order->id)->pluck('product_id')->toArray();

            $products = Product::whereIn('id',$order_prd_id)->get();
            foreach($products as $prd_key => $product)
            {
                $order_per = OrderProducts::where('product_id',$product->id)->sum('sub_total');
                
                if(isset($product->commissionType) && $product->commissionType->commission_type=='p'){
                    $get_prod_commission = $product->commission_value/100;
                    $percentage_value    = $order_per*$get_prod_commission;
                }else if(isset($product->commissionType) && $product->commissionType->commission_type=='f'){
                    $fixed_value = $product->commission_value;
                }
                $p_commission = (isset($percentage_value)?$percentage_value:0)+(isset($fixed_value)?$fixed_value:0);
            }

            $employee = Employee::find($emp_id);

            if(isset($employee->baseCommission) && $employee->baseCommission->commission_type=='f'){
                $commission = $p_commission*$employee->basic_commission_value;
            }elseif(isset($employee->baseCommission) && $employee->baseCommission->commission_type=='p'){
                $get_base_commission_value = $employee->basic_commission_value/100;
                $commission = $p_commission*$get_base_commission_value;
            }

            $targetCommissions  = Orders::where('id',$order->id)->where('sales_rep_id',$emp_id)
                                        ->where('order_status',13)
                                        ->whereMonth('order_completed_at',$pre_month)
                                        ->whereYear('order_completed_at',$year)->sum('total_amount');
            $t_commission       = (float)$targetCommissions;
            $target_commissions = 0;

            if($targetCommissions!=0){
                if($t_commission>=$employee->target_value){
                    if(isset($employee->targetCommission) && $employee->targetCommission->commission_type=='f'){
                        $target_commissions = $employee->target_commission_value;
                    }elseif(isset($employee->targetCommission) && $employee->targetCommission->commission_type=='p'){
                        $get_target_commission_value = $employee->target_commission_value/100;
                        $target_commissions = $t_commission*$get_target_commission_value;
                    }
                }
            }
            $commissions += $commission + $target_commissions;
        }
        return $commissions;
    }

}
