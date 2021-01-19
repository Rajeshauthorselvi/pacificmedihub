<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModificationsToProductVariantVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variant_vendors', function (Blueprint $table) {
            $table->dropColumn(['variant_id']);
            $table->float('base_price',8,2)->nullable();
            $table->float('retail_price',8,2)->nullable();
            $table->float('minimum_selling_price',8,2)->nullable();
            $table->boolean('display_variant')->default(1)->comment('0 - not display, 1 - display');
            $table->integer('display_order')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->bigInteger('vendor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variant_vendors', function (Blueprint $table) {
            $table->dropColumn(['base_price','retail_price','minimum_selling_price','display_variant','display_order','stock_quantity','vendor_id']);

        });
    }
}
