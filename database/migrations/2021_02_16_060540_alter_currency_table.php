<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currency', function (Blueprint $table) {
            $table->string('symbol',5)->nullable()->default(null)->change();
            $table->boolean('published')->default(0)->comment('0 - not published, 1 - published')->after('is_primary');
            $table->timestamp('created_at')->nullable()->after('published');
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->after('created_at');
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - deleted')->after('updated_at');
            $table->dateTime('deleted_at')->nullable()->after('is_deleted');
            $table->dropColumn(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currency', function (Blueprint $table) {
            //
        });
    }
}
