<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerOrderReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_order_return_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('customer_order_return_id');
            $table->integer('order_id');
            $table->integer('order_product_id');
            $table->integer('qty_received');
            $table->integer('damage_quantity')->nullable();
            $table->integer('missed_quantity')->nullable();
            $table->integer('return_quantity')->nullable();
            $table->integer('stock_quantity')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_order_return_products');
    }
}
