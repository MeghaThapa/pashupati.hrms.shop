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
        Schema::create('raw_material_stocks', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('godam_id')->index();
            $table->foreign('godam_id')->references('id')->on('godam');

            $table->unsignedBigInteger('dana_group_id')->index();
            $table->foreign('dana_group_id')->references('id')->on('dana_groups');
            $table->unsignedBigInteger('dana_name_id')->index();
            $table->foreign('dana_name_id')->references('id')->on('dana_names');
            $table->string('quantity');
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
        Schema::dropIfExists('raw_material_stocks');
    }
};
