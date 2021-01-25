<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariationIdToPurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            $table->integer('option_id');
            $table->integer('option_value_id');
            $table->integer('option_id2')->nullable();
            $table->integer('option_value_id2')->nullable();
            $table->integer('option_id3')->nullable();
            $table->integer('option_value_id3')->nullable();
            $table->integer('option_id4')->nullable();
            $table->integer('option_value_id4')->nullable();
            $table->integer('option_id5')->nullable();
            $table->integer('option_value_id5')->nullable();
            $table->integer('qty_received')->nullable();
            $table->integer('issue_quantity')->nullable();
            $table->string('reason',255)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_products', function (Blueprint $table) {
            //
        });
    }
}
