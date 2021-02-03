<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('rfq_id')->unsigned()->nullable();
            $table->foreign('rfq_id')->references('id')->on('rfq')->onDelete('cascade');
            
            $table->string('order_no', 100);

            $table->unsignedInteger('order_status')->unsigned();
            $table->foreign('order_status')->references('id')->on('order_status')->onDelete('cascade');

            $table->unsignedBigInteger('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('sales_rep_id')->unsigned();
            $table->foreign('sales_rep_id')->references('id')->on('employees')->onDelete('cascade');

            $table->unsignedInteger('order_tax')->nullable();
            $table->decimal('order_discount',8,2)->nullable();
            $table->longText('notes')->nullable();

            $table->text('payment_term')->nullable();
            $table->integer('payment_status')->comment('1->Paid,2->Partly Paid,3->Not Paid');
            $table->string('payment_ref_no',100)->nullable();
            $table->decimal('paid_amount',8,2)->nullable();

            $table->unsignedInteger('paying_by')->unsigned()->nullable();
            $table->foreign('paying_by')->references('id')->on('payment_methods')->onDelete('cascade');

            $table->longText('payment_note')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
