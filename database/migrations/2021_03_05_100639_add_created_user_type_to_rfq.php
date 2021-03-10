<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedUserTypeToRfq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq', function (Blueprint $table) {
            $table->integer('created_user_type')->after('user_id')->comment('1->Admin,2->Employee,3->Customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rfq', function (Blueprint $table) {
            //
        });
    }
}
