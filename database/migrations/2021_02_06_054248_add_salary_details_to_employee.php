<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalaryDetailsToEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('emp_account_number')->nullable()->default(null)->change();
            $table->string('emp_bank_name')->nullable()->default(null)->change();
            $table->string('emp_bank_branch')->nullable()->default(null)->change();
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
        Schema::table('employees', function (Blueprint $table) {
            Schema::dropIfExists(['self_cpf','emp_cpf','sdl','emp_account_number','emp_bank_name','emp_bank_branch']);
        });
    }
}
