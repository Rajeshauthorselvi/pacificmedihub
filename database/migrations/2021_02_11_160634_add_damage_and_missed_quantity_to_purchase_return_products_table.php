<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDamageAndMissedQuantityToPurchaseReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_return_products', function (Blueprint $table) {
            $table->integer('damage_quantity')->after('purchase_variation_id')->nullable();
            $table->integer('missed_quantity')->after('purchase_variation_id')->nullable();
            $table->dropColumn(['return_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_return_products', function (Blueprint $table) {
            //
        });
    }
}
