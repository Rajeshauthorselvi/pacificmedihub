<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_id');
            $table->string('sku',20)->nullable();
            $table->string('dosage',255);
            $table->string('package',255);
            $table->float('base_price',8,2)->nullable();
            $table->float('retail_price',8,2)->nullable();
            $table->float('mininum_selling_price',8,2)->nullable();
            $table->boolean('default_variant')->default(0)->comment('0 - not set, 1 - set to default');
            $table->boolean('display_variant')->default(0)->comment('0 - not display, 1 - display');
            $table->bigInteger('display_order')->nullable();
            $table->bigInteger('stock_quantity')->nullable();
            $table->integer('alert_quantity')->nullable();
            $table->string('vendor_ids',255)->nullable();
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
        Schema::dropIfExists('product_variants');
    }
}
