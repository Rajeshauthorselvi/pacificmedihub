<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->bigInteger('ref_id')->after('id');
            $table->enum('type',['register','rfq','order'])->default('rfq')->after('ref_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification', function (Blueprint $table) {
            //
        });
    }
}
