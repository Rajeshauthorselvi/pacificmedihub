<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use App\Models\Employee;
use App\Models\OrderStatus;
use App\Models\OrderProducts;
use App\Models\ProductVariantVendor;
class Orders extends Model
{
    public function customer()
    {
    	return $this->belongsTo(User::class,'customer_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class,'order_id');
    }

    public function salesrep()
    {
    	return $this->belongsTo(Employee::class,'sales_rep_id');
    }
    public function statusName()
    {
    	return $this->belongsTo(OrderStatus::class,'order_status');
    }

    public function currencyCode()
    {
        return $this->belongsTo(Currency::class,'currency');
    }

    public function oderTax()
    {
        return $this->belongsTo(Tax::class,'order_tax');
    }

    public function payTerm()
    {
        return $this->belongsTo(PaymentTerm::class,'payment_term');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function paidAmount()
    {
        return $this->belongsTo(PaymentMethod::class,'paying_by');
    }
    public function deliveryPerson()
    {
        return $this->belongsTo(Employee::class,'delivery_person_id');
    }
    public function deliveryStatus()
    {
        return $this->belongsTo(OrderStatus::class,'delivery_status');
    }

    static function CheckQuantity($order_id)
    {
        $products =self::with('orderProducts')
                    ->where('orders.id',$order_id)
                    ->where('order_status','<>',14)
                    ->where('delivery_person_id',0)->first();

        $product_data=array();
        $check_quantity=array();
        if (isset($products->orderProducts)) {
            foreach ($products->orderProducts as $key => $product) {
              // var_dump($product->product_variation_id)
              // $all_variants=self::AllProductVariantIds($product->product_id,$product->product_variation_id);

                $check_product_quantity=ProductVariantVendor::where('product_variant_id',$product->product_variation_id)
                                        ->sum('stock_quantity');
                if ($product->quantity > $check_product_quantity) {
                    array_push($check_quantity, 'yes');
                }
            }
        }

        return array_unique($check_quantity);
    }

    static function LowStockQuantity()
    {
        $products=Product::select('id','name','alert_quantity')->whereNotNull('alert_quantity')->get();
        $avalivle_low_quantity=$stock_details=array();
        $count=0;
        foreach ($products as $key => $product) {
            $query=DB::table('product_variant_vendors as pvv')
                   ->leftjoin('product_variants as pv','pv.id','pvv.product_variant_id')
                   ->where('pv.product_id',$product->id)
                   ->where('pvv.stock_quantity','<=',$product->alert_quantity)
                   ->where('pv.disabled',0)
                   ->where('pv.is_deleted',0)
                   ->get();
                   foreach ($query as $key => $qq) {
                       $check_purchase_exists=DB::table('purchase as p')
                                              ->leftjoin('purchase_products as pp','p.id','pp.purchase_id')
                                              ->where('pp.product_id',$product->id)
                                              ->where('pp.product_variation_id',$qq->id)
                                              ->where('p.purchase_status','<>',2)
                                              ->exists();
                        if (!$check_purchase_exists) {
                             array_push($avalivle_low_quantity, $qq);
                             $stock_details[$product->name]=$avalivle_low_quantity;
                             $count +=1;
                        }
                   }
        }

        return ['low_stock_count'=>$count,'stock_details'=>$stock_details];
    }

    static function AllProductVariantIds($product_id,$product_variation_id)
    {
              $check_variant_vendors=DB::table('product_variant_vendors')
                                                ->where('product_id',$product_id)
                                                ->where('product_variant_id',$product_variation_id)
                                                ->value('product_variant_id');
                $variants=DB::table('product_variants')
                                       ->where('id',$check_variant_vendors)
                                       ->first();
                $all_variants=DB::table('product_variants')
                              ->where('product_id',$product_id)
                              ->where('option_id',$variants->option_id)
                              ->where('option_value_id',$variants->option_value_id)
                              ->where('option_id2',$variants->option_id2)
                              ->where('option_value_id2',$variants->option_value_id2)
                              ->where('option_id3',$variants->option_id3)
                              ->where('option_value_id3',$variants->option_value_id3)
                              ->where('option_id4',$variants->option_id4)
                              ->where('option_value_id4',$variants->option_value_id4)
                              ->where('option_id5',$variants->option_id5)
                              ->where('option_value_id5',$variants->option_value_id5)
                              ->pluck('id')->toArray();

        return $all_variants;
    }
    static function LowQuantityInStockVerifyPage($product_id,$product_variant_id)
    {
        
    }
}
