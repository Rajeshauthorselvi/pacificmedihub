<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rfq_id');
            $table->integer('product_id');
            $table->integer('product_variant_id');
            $table->decimal('base_price',10,2);
            $table->decimal('retail_price',10,2);
            $table->decimal('minimum_selling_price',10,2);
            $table->integer('quantity');
            $table->integer('sub_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfq_products');
    }
}
