<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',150)->nullable();
            $table->string('uen',150);
            $table->string('name',150);
            $table->string('email',255)->unique();
            $table->string('contact_number',20)->nullable();
            $table->string('logo_image',100)->nullable();
            $table->string('gst_no',50)->nullable();
            $table->string('gst_image',100)->nullable();
            $table->string('pan_no',50)->nullable();
            $table->string('pan_image',100)->nullable();
            $table->string('address_line1',255)->nullable();
            $table->string('address_line2',255)->nullable();
            $table->string('post_code',25)->nullable();
            $table->string('country',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('account_name',100)->nullable();
            $table->string('account_number',100)->nullable();
            $table->string('bank_name',100)->nullable();
            $table->string('bank_branch',100)->nullable();
            $table->string('paynow_contact_number',20)->nullable();
            $table->string('bank_place',100)->nullable();
            $table->string('others',255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->boolean('status')->default(0)->comment('0 - not approved, 1 - approved');
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
        Schema::dropIfExists('vendors');
    }
}
