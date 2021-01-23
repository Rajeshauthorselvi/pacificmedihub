<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpSalaryStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emp_salary_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->biginteger('emp_id');
            $table->float('paid_amount',8,2);
            $table->date('paid_date');
            $table->boolean('status')->default(0)->comment('0 - not paid, 1 - paid');
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
        Schema::dropIfExists('emp_salary_status');
    }
}
