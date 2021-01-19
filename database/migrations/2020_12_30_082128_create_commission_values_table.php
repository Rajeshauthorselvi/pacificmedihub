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
            $table->bigIncrements('id');
            $table->unsignedInteger('commission_id');
            // $table->foreign('commission_id')->references('id')->on('commissions');
            $table->enum('commission_type', ['p','f'])->comment('P->Percentage, F->Fixed');
            $table->float('commission_value', 8, 2);
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
        Schema::dropIfExists('commission_values');
    }
}
