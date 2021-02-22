<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChangesToRfqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq', function (Blueprint $table) {
            $table->renameColumn('discount', 'order_discount');
            $table->Integer('order_tax')->nullable()->after('sales_rep_id');
            $table->Integer('payment_term')->nullable()->after('order_tax');
            $table->Integer('payment_status')->nullable()->after('payment_term');
            $table->dropColumn(['tax']);
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
