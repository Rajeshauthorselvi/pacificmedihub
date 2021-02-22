<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_company_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_name',255);
            $table->integer('parent_company');
            $table->string('company_gst',255);
            $table->string('telephone',255);
            $table->string('company_email',100);
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->string('post_code',50)->nullable();
            $table->integer('country_id');
            $table->integer('state_id');
            $table->integer('city_id');
            $table->string('logo',100);
            $table->integer('sales_rep');
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
        Schema::dropIfExists('user_company_details');
    }
}
