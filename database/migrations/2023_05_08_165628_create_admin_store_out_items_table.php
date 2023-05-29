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
        Schema::create('admin_store_out_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->index();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('department_id')->index();
            $table->foreign('department_id')->references('id')->on('department');
            $table->unsignedBigInteger('placement_id')->index();
            $table->foreign('placement_id')->references('id')->on('placements');
            $table->string('unit');
            $table->string('size');
            $table->string('quantity');
            $table->string('rate');
            $table->string('through');
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
        Schema::dropIfExists('admin_store_out_items');
    }
};
