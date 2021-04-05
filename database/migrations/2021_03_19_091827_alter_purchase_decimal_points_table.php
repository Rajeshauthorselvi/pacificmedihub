<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPurchaseDecimalPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase', function (Blueprint $table) {
            $table->decimal('order_tax_amount', 25,2)->change();
            $table->decimal('order_discount', 25,2)->change();
            $table->decimal('total_amount', 25,2)->change();
            $table->decimal('sgd_total_amount', 25,2)->change();
            $table->decimal('amount', 25,2)->change();
        });
        
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->decimal('base_price', 25,2)->change();
            $table->decimal('retail_price', 25,2)->change();
            $table->decimal('minimum_selling_price', 25,2)->change();
            $table->decimal('discount', 25,2)->change();
            $table->decimal('product_tax', 25,2)->change();
            $table->decimal('sub_total', 25,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase', function (Blueprint $table) {
            //
        });
    }
}
