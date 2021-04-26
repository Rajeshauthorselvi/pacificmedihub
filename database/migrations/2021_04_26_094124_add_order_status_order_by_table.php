<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\OrderStatus;
class AddOrderStatusOrderByTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_status', function (Blueprint $table) {
           $table->integer('order_by')->after('color_codes');
        });
        OrderStatus::where('id',18)->update(['order_by'=>2]);
        OrderStatus::where('id',19)->update(['order_by'=>1]);
        OrderStatus::where('id',20)->update(['order_by'=>3]);
        OrderStatus::where('id',21)->update(['order_by'=>4]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_status', function (Blueprint $table) {
            //
        });
    }
}
