<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDecimalLimitToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('order_discount', 25,2)->change();
            $table->decimal('total_amount', 25,2)->change();
            $table->decimal('sgd_total_amount', 25,2)->change();
            $table->decimal('exchange_total_amount', 25,2)->change();
            $table->decimal('paid_amount', 25,2)->change();
            $table->decimal('order_tax_amount', 25,2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
