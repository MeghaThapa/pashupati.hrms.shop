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

        Schema::create('storein_item', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('storein_id');
            $table->foreign('storein_id')->references('id')->on('storein')->cascadeOnDelete();

            $table->unsignedBigInteger('storein_category_id');
            $table->foreign('storein_category_id')->references('id')->on('storein_categories');

            $table->unsignedBigInteger('storein_item_id');
            $table->foreign('storein_item_id')->references('id')->on('items_of_storeins');

            $table->unsignedBigInteger('size_id');
            $table->foreign('size_id')->references('id')->on('sizes');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');

             $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('storein_departments');

            $table->string('quantity');
            $table->string('price');
            $table->string('total_amount')->default('0');
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
        Schema::dropIfExists('admin_storein_item');
    }
};
