<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpCommissionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_commission_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('emp_id');
            $table->bigInteger('basic_commission_type')->nullable();
            $table->float('basic_commission_value', 8,2)->nullable();
            $table->bigInteger('target_commission_type')->nullable();
            $table->float('target_commission_value', 8,2)->nullable();
            $table->decimal('target_value', 10,2)->nullable();
            $table->date('modified_date')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emp_commission_history');
    }
}
