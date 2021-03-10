<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSliderBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slider_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('slider_id');
            $table->string('images',255);
            $table->string('title',255)->nullable();
            $table->text('description')->nullable();
            $table->string('button',255)->nullable();
            $table->string('link',255)->nullable();
            $table->bigInteger('display_order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider_banners');
    }
}
