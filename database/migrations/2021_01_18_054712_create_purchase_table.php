<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('purchase_date');
            $table->string('purchase_order_number',100)->nullable();
            $table->integer('purchase_status');
            $table->integer('vendor_id');
            $table->integer('order_tax')->nullable();
            $table->float('order_discount',8,2)->nullable();
            $table->integer('payment_term')->nullable();
            $table->integer('payment_status');
            $table->string('payment_reference_no',150)->nullable();
            $table->float('amount',8,2)->nullable();
            $table->integer('paying_by')->nullable();
            $table->mediumText('payment_note')->nullable();
            $table->mediumText('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase');
    }
}
