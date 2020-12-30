<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_values', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('commission_id');
            $table->foreign('commission_id')->references('id')->on('commissions');
            $table->enum('commission_type', ['p','f'])->comment('P->Percentage, F->Fixed');
            $table->float('commission_value', 8, 2);
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
        Schema::dropIfExists('commission_values');
    }
}
