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
        Schema::create('store_out_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_of_storein_id')->index();
            $table->foreign('item_of_storein_id')->references('id')->on('items_of_storeins');

            $table->unsignedBigInteger('storeinDepartment_id')->index();
            $table->foreign('storeinDepartment_id')->references('id')->on('storein_departments');

            $table->unsignedBigInteger('placement_id')->index();
            $table->foreign('placement_id')->references('id')->on('placements');

             $table->unsignedBigInteger('storeout_id')->index();
            $table->foreign('storeout_id')->references('id')->on('storeout')->cascadeOnDelete();

            $table->string('unit');
            $table->string('size');
            $table->string('quantity');
            $table->string('rate');
            $table->string('through')->nullable();
            $table->string('total');
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
        //
    }
};
