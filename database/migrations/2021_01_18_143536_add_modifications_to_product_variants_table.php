<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModificationsToProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {

            $table->dropColumn(['base_price','retail_price','minimum_selling_price','display_variant','display_order','stock_quantity','vendor_id']);
            $table->integer('option_id2')->nullable();
            $table->integer('option_value_id2')->nullable();
            $table->integer('option_id3')->nullable();
            $table->integer('option_value_id3')->nullable();
            $table->integer('option_id4')->nullable();
            $table->integer('option_value_id4')->nullable();
            $table->integer('option_id5')->nullable();
            $table->integer('option_value_id5')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['option_id2','option_value_id2','option_id3','option_value_id3','option_id4','option_value_id4','option_id5','option_value_id5']);
        });
    }
}
