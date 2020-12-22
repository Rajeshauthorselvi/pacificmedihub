<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('first_name',50);
            $table->string('last_name',50)->nullable();
            $table->enum('gender',['Male','Female'])->comment('M- male, F - female')->nullable();
            $table->date('dob')->nullable();
            $table->string('email',255)->unique();
            $table->string('contact_number',20)->nullable();
            $table->string('profile_image',50)->nullable();
            $table->string('password',255);
            $table->string('remember_token',255)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->boolean('status')->default(0)->comment('0 - not visible, 1 - visible');
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - is deleted');
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
