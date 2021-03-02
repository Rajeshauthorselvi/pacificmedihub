<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGoodsTypeToPurchaseStockHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_stock_history', function (Blueprint $table) {
            $table->enum('goods_type',[1,2])
                  ->comment('1->Goods Return,2-> Goods Replace')
                  ->after('stock_quantity')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_stock_history', function (Blueprint $table) {
            //
        });
    }
}
