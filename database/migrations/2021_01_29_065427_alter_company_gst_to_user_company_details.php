<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCompanyGstToUserCompanyDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_company_details', function (Blueprint $table) {
            $table->string('company_gst',255)->nullable()->change();
            $table->string('logo',100)->nullable()->change();
            $table->integer('sales_rep')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_company_details', function (Blueprint $table) {
            //
        });
    }
}
