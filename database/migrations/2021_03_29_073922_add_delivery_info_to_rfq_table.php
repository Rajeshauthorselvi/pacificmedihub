<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryInfoToRfqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq', function (Blueprint $table) {
            $table->integer('delivery_method_id')->nullable()->after('order_discount');
            $table->double('delivery_charge',10,2)->nullable()->after('delivery_method_id');
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
