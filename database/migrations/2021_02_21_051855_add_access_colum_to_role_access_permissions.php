<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccessColumToRoleAccessPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_access_permissions', function (Blueprint $table) {
            $table->enum('allow_access',['yes','no'])->nullable()->after('operation');
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
