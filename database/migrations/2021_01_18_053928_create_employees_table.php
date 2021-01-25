<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('emp_id',30)->nullable();
            $table->string('emp_name',255);
            $table->bigInteger('emp_department');
            $table->string('emp_designation',255);
            $table->string('emp_identification_no',255)->nullable();
            $table->date('emp_doj')->nullable();
            $table->string('emp_mobile_no',15);
            $table->string('emp_email',255);
            $table->string('emp_address_line1',255);
            $table->string('emp_address_line2',255)->nullable();
            $table->string('emp_postcode',25);
            $table->bigInteger('emp_country');
            $table->bigInteger('emp_state')->nullable();
            $table->bigInteger('emp_city')->nullable();
            $table->string('emp_image',100)->nullable();
            $table->string('emp_account_name',100)->nullable();
            $table->string('emp_account_number',100);
            $table->string('emp_bank_name',100);
            $table->string('emp_bank_branch',100);
            $table->string('emp_ifsc_code',100)->nullable();
            $table->string('emp_paynow_contact_number',20)->nullable();
            $table->float('basic',8,2)->nullable();
            $table->float('hr',8,2)->nullable();
            $table->float('da',8,2)->nullable();
            $table->float('conveyance',8,2)->nullable();
            $table->float('esi',8,2)->nullable();
            $table->float('pf',8,2)->nullable();
            $table->integer('basic_commission_type')->nullable();
            $table->float('basic_commission_value',8,2)->nullable();
            $table->float('target_value',8,2)->nullable();
            $table->integer('target_commission_type')->nullable();
            $table->float('target_commission_value',8,2)->nullable();
            $table->boolean('status')->default(0)->comment('0 - not approved, 1 - approved');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - is deleted');
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
        Schema::dropIfExists('employees');
    }
}
