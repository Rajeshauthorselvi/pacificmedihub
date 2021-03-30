<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAndInsertNewColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_name','gender','dob','company_id','poc_id','customer_uen']);
            $table->renameColumn('first_name','name');
            $table->renameColumn('profile_image','logo');
            $table->bigInteger('parent_company')->default(0);
            $table->string('company_gst',255)->nullable();
            $table->string('company_gst_certificate',255)->after('company_gst')->nullable();
            $table->string('company_uen', 255)->nullable()->after('company_gst_certificate');
            $table->text('address_1')->nullable()->after('company_uen');
            $table->text('address_2')->nullable()->after('address_1');
            $table->string('post_code',50)->nullable()->after('address_2');
            $table->integer('country_id')->nullable()->after('post_code');
            $table->integer('state_id')->nullable()->after('country_id');
            $table->integer('city_id')->nullable()->after('state_id');
            $table->string('latitude',255)->nullable()->after('city_id');
            $table->string('longitude',255)->nullable()->after('latitude');
            $table->integer('sales_rep')->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
