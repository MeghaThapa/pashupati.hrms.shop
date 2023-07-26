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
        Schema::create('nonwoven_opening_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('receive_date');
            $table->string('receive_no');
            $table->string('fabric_roll');
            $table->string('fabric_gsm');
            $table->string('fabric_name');
            $table->string('fabric_color');
            $table->string('length');
            $table->string('gross_weight');
            $table->string('net_weight');
            // $table->string('dana_quantity');

            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references("id")->on('godam')->onDelete('cascade');

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
        Schema::dropIfExists('nonwoven_opening_stocks');
    }
};
