<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureBlockDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_block_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('feature_id');
            $table->string('title',255)->nullable();
            $table->text('message')->nullable();
            $table->string('images',255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_block_data');
    }
}
