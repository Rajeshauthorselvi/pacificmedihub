<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_category_id')->nullable();
            $table->string('name',150);
            $table->string('image',150)->nullable();
            $table->text('description')->nullable();
            $table->boolean('published')->default(0)->comment('0 - not published, 1 - published');
            $table->boolean('show_home')->default(0)->comment('0 - not show, 1 - show');
            $table->integer('display_order')->nullable();
            $table->string('search_engine_name',150)->nullable();
            $table->string('meta_title',150)->nullable();
            $table->string('meta_keyword',150)->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->boolean('is_deleted')->default(0)->comment('0 - not deleted, 1 - deleted');
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
        Schema::dropIfExists('categories');
    }
}
