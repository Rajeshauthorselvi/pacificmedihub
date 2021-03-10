<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRfqCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfq_comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('rfq_id');
            $table->foreign('rfq_id')->references('id')->on('rfq');
            $table->text('comment')->nullable();
            $table->string('attachment_name',255)->nullable();
            $table->integer('commented_by');
            $table->integer('commented_by_user_type')->comment('1->Admin,2->Employee,3->Customer');
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rfq_comments');
    }
}
