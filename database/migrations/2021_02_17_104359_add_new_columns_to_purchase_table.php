<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToPurchaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase', function (Blueprint $table) {
            $table->decimal('order_tax_amount',10,3)->nullable()->default(null)->after('order_tax');
            $table->decimal('total_amount',10,3)->nullable()->default(null)->after('order_discount');
            $table->decimal('sgd_total_amount',10,3)->nullable()->default(null)->after('total_amount');
            $table->bigInteger('user_id')->nullable()->default(null)->after('stock_notes');
            $table->timestamp('created_at')->nullable()->after('user_id');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase', function (Blueprint $table) {
            //
        });
    }
}
