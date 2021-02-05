<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableBankAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('account_name')->nullable()->default(null)->change();
            $table->string('account_number')->nullable()->default(null)->change();
            $table->string('bank_name')->nullable()->default(null)->change();
            $table->string('bank_branch')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_accounts', function (Blueprint $table) {
            Schema::dropIfExists(['account_name','account_number','bank_name','bank_branch']);
        });
    }
}
