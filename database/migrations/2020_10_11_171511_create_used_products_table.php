<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('used_products', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('finished_id')->unsigned()->index();
            $table->foreign('finished_id')->references('id')->on('finished_products')->onDelete('cascade');

            $table->bigInteger('purchase_pro_id')->unsigned()->index();
            $table->foreign('purchase_pro_id')->references('id')->on('purchase_products')->onDelete('cascade');

            $table->float('used_quantity', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('used_products');
    }
}
