<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleDetailsToRoleAccessPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_access_permissions', function (Blueprint $table) {
            $table->string('object', 100)->nullable()->after('role_id');
            $table->string('operation', 100)->nullable()->after('object');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_access_permissions', function (Blueprint $table) {
            //
        });
    }
}
