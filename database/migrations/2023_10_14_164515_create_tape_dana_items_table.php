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
        Schema::create('tape_dana_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tape_entry_id');
            $table->integer('from_godam_id');
            $table->integer('plant_type_id');
            $table->integer('plant_name_id');
            $table->integer('shift_id');
            $table->integer('dana_group_id');
            $table->integer('dana_name_id');
            $table->integer('quantity');
            $table->integer('autoload_id');
            $table->foreign('tape_entry_id')->references('id')->on('tape_entry')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('tape_dana_items');
    }
};
