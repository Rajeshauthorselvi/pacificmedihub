<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDeliveryZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_zone', function (Blueprint $table) {
            $table->dropColumn(['created_at','updated_at','is_deleted','deleted_at']);
            $table->bigInteger('region_id')->after('id');
            $table->float('delivery_fee',8,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_zone', function (Blueprint $table) {
            //
        });
    }
}
