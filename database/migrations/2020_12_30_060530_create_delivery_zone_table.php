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
            $table->increments('id');
            $table->integer('post_code')->unsigned();
            $table->float('delivery_fee')->unsigned();
            $table->enum('status', [1,2,3])->comment('1->Active,2->InActive,3->Deleted');
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
        Schema::dropIfExists('delivery_zone');
    }
}
