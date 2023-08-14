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
        Schema::create('fabric_send_and_receive_dana_consumption_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fsr_entry");
            $table->foreign("fsr_entry")->references("id")->on("fabric_send_and_receive_entry")->onDelete("cascade");
            $table->unsignedBigInteger("godam_id");
            $table->foreign("godam_id")->references("id")->on("godam")->onDelete("cascade");
            $table->unsignedBigInteger("dana_name");
            $table->foreign('dana_name')->references("id")->on("dana_names")->onDelete("cascade");
            $table->unsignedBigInteger("dana_group");
            $table->foreign('dana_group')->references("id")->on("dana_groups")->onDelete("cascade");
            $table->string("consumption_quantity");
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
        Schema::dropIfExists('fabric_send_and_receive_dana_consumption_list');
    }
};
