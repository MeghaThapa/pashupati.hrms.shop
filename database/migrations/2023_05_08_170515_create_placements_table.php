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
        Schema::create('placements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('storeout_dpt_id')->index();
            $table->foreign('storeout_dpt_id')->references('id')->on('storeout_departments');

            $table->unsignedBigInteger('godam_id')->index();
            $table->foreign('godam_id')->references('id')->on('godam');
            $table->enum('status',['active','inactive']);
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
        Schema::dropIfExists('placements');
    }
};
