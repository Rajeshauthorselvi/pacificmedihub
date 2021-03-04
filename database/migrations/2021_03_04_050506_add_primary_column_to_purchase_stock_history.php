<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrimaryColumnToPurchaseStockHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_stock_history', function (Blueprint $table) {
            $table->enum('is_primary',[1,2])->default(2)->after('goods_type')->comment('1->Primary Amount,2->Return or Replace Amount');
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
