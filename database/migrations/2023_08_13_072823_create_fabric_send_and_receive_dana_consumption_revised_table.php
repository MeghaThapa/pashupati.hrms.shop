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
        Schema::create('fabric_send_and_receive_dana_consumption', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fsr_entry_id");
            $table->foreign("fsr_entry_id")->references("id")->on("fabric_send_and_receive_entry");
            $table->unsignedBigInteger("dana_name_id");
            $table->foreign('dana_name_id')->references("id")->on("dana_names")->onDelete("cascade");
            $table->unsignedBigInteger("dana_group_id");
            $table->foreign('dana_group_id')->references("id")->on("dana_groups")->onDelete("cascade");
            $table->string("consumption_quantity");
            $table->string("autoloader_id");
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
        Schema::dropIfExists('fabric_send_and_receive_dana_consumption');
    }
};
