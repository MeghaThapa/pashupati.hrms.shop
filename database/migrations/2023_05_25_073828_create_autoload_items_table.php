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
        Schema::create('autoload_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_godam_id')->index();
            $table->foreign('from_godam_id')->references('id')->on('godam');
            $table->unsignedBigInteger('plant_type_id')->index();
            $table->foreign('plant_type_id')->references('id')->on('processing_steps');
            $table->unsignedBigInteger('plant_name_id')->index();
            $table->foreign('plant_name_id')->references('id')->on('processing_subcats');
            $table->unsignedBigInteger('shift_id')->index();
            $table->foreign('shift_id')->references('id')->on('shifts');
            $table->unsignedBigInteger('dana_group_id')->index();
            $table->foreign('dana_group_id')->references('id')->on('dana_groups');
            $table->unsignedBigInteger('dana_name_id')->index();
            $table->foreign('dana_name_id')->references('id')->on('dana_names');
            $table->string('quantity');
            $table->unsignedBigInteger('autoload_id')->index();
            $table->foreign('autoload_id')->references('id')->on('auto_loads');
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
        Schema::dropIfExists('autoload_items');
    }
};
