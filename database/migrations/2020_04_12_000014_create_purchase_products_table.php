<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_id')->unsigned()->index();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->string('product_name');
            $table->float('quantity', 10, 2);
            $table->float('used_quantity', 10, 2)->nullable();
            $table->float('return_quantity', 10, 2)->nullable();
            $table->float('damage_quantity', 10, 2)->nullable();
            $table->string('unit');
            $table->float('unit_price', 10, 2);
            $table->float('discount', 10, 2)->nullable();
            $table->float('total', 10, 2);
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
        Schema::dropIfExists('purchase_products');
    }
}
