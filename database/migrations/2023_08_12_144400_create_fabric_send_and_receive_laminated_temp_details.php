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
        Schema::create('fabric_send_and_receive_laminated_temp_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("temp_lam");
            $table->foreign("temp_lam")->references("id")->on("fabric_send_and_receive_temp_lam")->onDelete("cascade");
            $table->string("standard_wt");
            $table->unsignedBigInteger("fbgrp_id");
            $table->foreign("fbgrp_id")->references("id")->on("fabric_groups")->onDelete("cascade");
            $table->string('gram_wt');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('roll_no');
            $table->string('loom_no');
            $table->string('average_wt');
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
        Schema::dropIfExists('fabric_send_and_receive_laminated_temp_details');
    }
};
