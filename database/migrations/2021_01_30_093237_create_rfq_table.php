<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_no',100);
            $table->integer('status');
            $table->integer('customer_id');
            $table->integer('sales_rep_id');
            $table->decimal('discount',10,2)->nullable();
            $table->decimal('tax',10,2)->nullable();
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('rfq');
    }
}
