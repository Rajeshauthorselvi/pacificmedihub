<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('purchase_id');
            $table->integer('product_id');
            $table->date('expiry_date')->nullable();
            $table->decimal('base_price',8,2);
            $table->decimal('retail_price',8,2);
            $table->decimal('minimum_selling_price',8,2);
            $table->integer('quantity');
            $table->decimal('discount',8,2);
            $table->decimal('product_tax',8,2);
            $table->decimal('sub_total',8,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}
