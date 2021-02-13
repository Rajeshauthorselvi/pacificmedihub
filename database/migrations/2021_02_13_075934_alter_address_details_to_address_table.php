<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAddressDetailsToAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->integer('country_id')->nullable()->default(null)->change();
            $table->integer('state_id')->nullable()->default(null)->change();
            $table->integer('city_id')->nullable()->default(null)->change();
            $table->integer('post_code')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            Schema::dropIfExists(['country_id','state_id','city_id']);
        });
    }
}
