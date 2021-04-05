<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColmnusToRfqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rfq', function (Blueprint $table) {
            $table->boolean('send_approval')->default(0)->comment('Child company request RFQ to parent company => [0 - Approval Not Send, 1 - Approval Sent]')->after('status');
            $table->integer('approval_status')->default(0)->comment('0 - Pending, 1 - Approved, 2 - Disapproved')->after('send_approval');
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
