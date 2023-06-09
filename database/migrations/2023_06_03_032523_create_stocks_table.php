<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->index();
            $table->foreign('department_id')->references('id')->on('storein_departments');
            $table->unsignedBigInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('storein_categories');
            $table->unsignedBigInteger('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items_of_storeins');
            $table->string('quantity');

             $table->unsignedBigInteger('size');
            $table->foreign('size')->references('id')->on('sizes');

            $table->unsignedBigInteger('unit');
            $table->foreign('unit')->references('id')->on('units');

            $table->string('avg_price');
            $table->string('total_amount');

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
        Schema::dropIfExists('stocks');
    }
};
