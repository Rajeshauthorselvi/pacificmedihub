<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToRfqAndOrderProductsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq_products', function (Blueprint $table) {
            $table->decimal('discount_value',8,2)->nullable()->after('rfq_price');
            $table->enum('discount_type',['percentage','amount'])->default('percentage')->after('discount_value');
            $table->decimal('final_price',10,2)->nullable()->after('discount_type');
            $table->decimal('total_price',10,2)->nullable()->after('final_price');
        });

        Schema::table('order_products', function (Blueprint $table) {
            $table->decimal('price',10,2)->nullable()->after('quantity');
            $table->decimal('discount_value',8,2)->nullable()->after('price');
            $table->enum('discount_type',['percentage','amount'])->default('percentage')->after('discount_value');
            $table->decimal('total_price',10,2)->nullable()->after('final_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfq_products', function (Blueprint $table) {
            //
        });
    }
}
