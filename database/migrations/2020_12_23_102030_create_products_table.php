<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',150);
            $table->string('code',150)->nullable();
            $table->string('sku',20)->nullable();
            $table->bigInteger('category_id');
            $table->bigInteger('brand_id');
            $table->string('short_description',255)->nullable();
            $table->text('long_description')->nullable();
            $table->text('treatment_information')->nullable();
            $table->text('dosage_instructions')->nullable();
            $table->integer('alert_quantity')->nullable();
            $table->boolean('commission_type')->default(0)->comment('0 - percentage(%), 1 - fixed(amount)');
            $table->float('commission_value',8,2)->nullable();
            $table->string('vendor_ids',255)->nullable();
            $table->boolean('published')->default(0)->comment('0 - not published, 1 - published');
            $table->boolean('show_home')->default(0)->comment('0 - not show, 1 - show');
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
        Schema::dropIfExists('products');
    }
}
