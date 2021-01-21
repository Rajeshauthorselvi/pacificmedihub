<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('purchase_or_order_id');
            $table->integer('customer_or_vendor_id');
            $table->integer('order_type')->comment('1->Purchase,2->Order')->nullable();
            $table->integer('payment_status')->comment('1->Paid,2->Not Paid')->nullable();
            $table->integer('return_status');
            $table->mediumText('return_notes')->nullable();
            $table->mediumText('staff_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_return');
    }
}
