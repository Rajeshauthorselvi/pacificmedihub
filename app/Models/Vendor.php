<?php

namespace App\Models;
use DB;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    static function TotalVendorSales($vendor_id)
    {
    	$total_purchase=DB::table('purchase as p')
    				    ->leftjoin('purchase_products as pp','pp.purchase_id','p.id')
    				    ->where('vendor_id',$vendor_id)
    				    ->sum('sub_total');

    	return $total_purchase;
    }
    static function TotalVendorOrder($vendor_id)
    {
    	$total_order=DB::table('purchase')->where('vendor_id',$vendor_id)->count();
    	return $total_order;
    }
    static function DueAmount($vendor_id)
    {
    	$total_due_amount=DB::table('purchase')
    					  ->where('vendor_id',$vendor_id)
    					  ->sum('amount');
    					  
    	return $total_due_amount;
    }
}
