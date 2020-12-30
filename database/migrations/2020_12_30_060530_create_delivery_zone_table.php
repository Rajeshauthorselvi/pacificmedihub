<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_zone', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('post_code')->unsigned();
            $table->float('delivery_fee')->unsigned();
            $table->boolean('published')->default(0)->comment('0 - not published, 1 - published');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - deleted');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_zone');
    }
}
