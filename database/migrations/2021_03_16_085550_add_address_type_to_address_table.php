<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressTypeToAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->integer('address_type')->default(0)->comment('0->default, 1-> delivery ,2->billing')->after('longitude');
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - is deleted')->after('updated_at');
            $table->dateTime('deleted_at')->nullable()->after('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            //
        });
    }
}
