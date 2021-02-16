<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToRfqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq', function (Blueprint $table) {
            $table->Integer('currency')->nullable()->default(null)->after('sales_rep_id');
            $table->decimal('order_tax_amount',10,3)->nullable()->default(null)->after('order_tax');
            $table->decimal('total_amount',10,3)->nullable()->default(null)->after('order_discount');
            $table->decimal('sgd_total_amount',10,3)->nullable()->default(null)->after('total_amount');
            $table->decimal('exchange_total_amount',10,3)->nullable()->default(null)->after('sgd_total_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfq', function (Blueprint $table) {
            //
        });
    }
}
