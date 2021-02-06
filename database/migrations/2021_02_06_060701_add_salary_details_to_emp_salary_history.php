<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalaryDetailsToEmpSalaryHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emp_salary_history', function (Blueprint $table) {
            $table->float('self_cpf', 8,2)->nullable()->after('basic');
            $table->float('emp_cpf', 8,2)->nullable()->after('self_cpf');
            $table->float('sdl', 8,2)->nullable()->after('emp_cpf');
            $table->dropColumn(['hr', 'da', 'conveyance', 'esi', 'pf']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emp_salary_history', function (Blueprint $table) {
            //
        });
    }
}
