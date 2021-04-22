<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use App\Models\Employee;
use App\Models\OrderStatus;
use App\Models\OrderProducts;
use App\Models\ProductVariantVendor;
use App\Models\PurchaseBatchInfo;
class Orders extends Model
{
  protected $fillable=['rfq_id','order_no','invoice_no','order_status','customer_id','sales_rep_id','currency','order_tax','order_tax_amount','order_discount','delivery_method_id','delivery_charge','total_amount','sgd_total_amount','exchange_total_amount','notes','user_id','delivery_address_id','billing_address_id','created_user_type','payment_term','payment_status','payment_ref_no','paid_amount','paying_by','payment_note','order_completed_at','delivered_at','created_at','updated_at','delivery_status','delivery_person_id','modified_logistic_id','logistic_instruction','approximate_delivery_date','address_id','quantity_deducted'];

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
    public function address()
    {
        return $this->belongsTo(UserAddress::class,'address_id');
    }
    public function deliveryMethod()
    {
        return $this->belongsTo(DeliveryMethod::class,'delivery_method_id');
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

    static function LowStockQuantity($type="products",$product_id=0)
    {
      if ($product_id==0) {
        $products=Product::select('id','name','alert_quantity')->whereNotNull('alert_quantity')->get();
      }
      else{
        $products=Product::select('id','name','alert_quantity')->where('id',$product_id)->get();
      }


        $avalivle_low_quantity=$stock_details=array();
        $count=0;
        foreach ($products as $key => $product) {
 /*           $query=DB::table('product_variant_vendors as pvv')
                   ->leftjoin('product_variants as pv','pv.id','pvv.product_variant_id')
                   ->where('pv.product_id',$product->id)
                   ->where('pvv.stock_quantity','<',$product->alert_quantity)
                   ->where('pv.disabled',0)
                   ->where('pv.is_deleted',0)
                   ->orderBy('id','ASC')
                   ->groupBy('pvv.product_variant_id')
                   ->pluck('stock_quantity','pvv.id')
                   ->toArray();*/

                    $query_total=DB::select("SELECT SUM(`stock_quantity`) as total,product_variant_id FROM `product_variant_vendors` WHERE `product_id`='".$product->id."' GROUP BY product_variant_id");

                    $query_exists=json_decode(json_encode($query_total), true);
                   if ($type=="products") {


                      $data_check=self::CheckValue($query_exists,$product->alert_quantity);

                      if (!$data_check) {
                        $check_purchase_exists=DB::table('purchase as p')
                                              ->leftjoin('purchase_products as pp','p.id','pp.purchase_id')
                                              ->where('pp.product_id',$product->id)
                                              ->where('p.purchase_status','<>',2)
                                              ->exists();
                        if (!$check_purchase_exists) {
                             $stock_details[$product->name]=$product;
                             $count +=1;
                        }
                      }
                   }
                   else{
                      $variant_ids=self::VariantsId($query_exists,$product->alert_quantity);
                         $check_purchase_exists=DB::table('purchase as p')
                                                ->leftjoin('purchase_products as pp','p.id','pp.purchase_id')
                                                ->where('pp.product_id',$product->id)
                                                ->where('p.purchase_status','<>',2)
                                                ->exists();
                          if (!$check_purchase_exists) {
                              $stock_details=$variant_ids;
                               $count +=1;
                          }


                   }
                  
        }
        return ['low_stock_count'=>$count,'stock_details'=>$stock_details];
    }
    static function VariantsId($total_Data,$alert_quantity)
    {
      $vids=array();
        foreach ($total_Data as $key => $ids) {
          if ($ids['total'] < $alert_quantity) {
            $vids[]=$ids['product_variant_id'];
          }
        }
      return $vids;
    }
  static function CheckValue($numbers,$alert_quantity)
      { 
         for ($i = 0; $i < sizeof($numbers) - 1; $i++)
            {
               if ($numbers[$i + 1]['total'] < $alert_quantity) 
               return false;
            }
          return true;
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
    static function GetRegion($postcode)
    {
        $region=DB::table('delivery_zone as dz')
                ->leftjoin('region as r','r.id','dz.region_id')
                ->where('post_code',$postcode)
                ->value('r.name');

        return $region;
    }
    static function PurchaseBatchInfo($product_id,$product_variant_id)
    {
        $batch_details=PurchaseBatchInfo::where([
                        'product_id'          => $product_id,
                        'product_variant_id'  => $product_variant_id
                      ])
                      ->get();
                      // 'batch_id','expiry_date'

        return $batch_details;
    }
}
