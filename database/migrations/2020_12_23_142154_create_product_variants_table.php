<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->float('base_price',8,2)->nullable();
            $table->float('retail_price',8,2)->nullable();
            $table->float('minimum_selling_price',8,2)->nullable();
            $table->integer('product_option_id1')->nullable();
            $table->integer('product_option_value_id1')->nullable();
            $table->integer('product_option_id2')->nullable();
            $table->integer('product_option_value_id2')->nullable();
            $table->integer('product_option_id3')->nullable();
            $table->integer('product_option_value_id3')->nullable();
            $table->boolean('display_variant')->default(0)->comment('0 - not display, 1 - display');
            $table->bigInteger('display_order')->nullable();
            $table->bigInteger('stock_quantity')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - deleted');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_variants');
    }
}
