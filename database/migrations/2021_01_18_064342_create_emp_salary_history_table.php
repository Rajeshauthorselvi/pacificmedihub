<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpSalaryHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_salary_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('emp_id');
            $table->float('basic',8,2)->nullable();
            $table->float('hr',8,2)->nullable();
            $table->float('da',8,2)->nullable();
            $table->float('conveyance',8,2)->nullable();
            $table->float('esi',8,2)->nullable();
            $table->float('pf',8,2)->nullable();
            $table->date('modified_date')->nullable();
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
        Schema::dropIfExists('emp_salary_history');
    }
}
