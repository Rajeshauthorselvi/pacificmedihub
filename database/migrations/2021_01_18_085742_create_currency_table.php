<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency_code',100);
            $table->string('currency_name',150);
            $table->string('symbol',5);
            $table->string('exchange_rate',100);
            $table->integer('is_primary')->comment('1->yes,2->no');
            $table->string('status')->comment('1->Active,2->Inactive,3->deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currency');
    }
}
