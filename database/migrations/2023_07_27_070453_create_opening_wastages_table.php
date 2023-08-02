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
        Schema::create('opening_wastages', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('godam_id');
            $table->foreign("godam_id")->references('id')->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger('waste_id');
            $table->foreign("waste_id")->references('id')->on('wastages');
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
        Schema::dropIfExists('opening_wastages');
    }
};
