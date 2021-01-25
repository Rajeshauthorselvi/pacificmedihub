<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->string('account_name',255);
            $table->string('account_number',255);
            $table->string('bank_name',255);
            $table->string('bank_branch',255);
            $table->string('paynow_contact',255)->nullable();
            $table->string('place',255)->nullable();
            $table->text('others',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
