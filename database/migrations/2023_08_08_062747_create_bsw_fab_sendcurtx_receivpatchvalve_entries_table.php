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
        Schema::create('bsw_fab_sendcurtx_receivpatchvalve_entries', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no');
            $table->string('date');
            $table->unsignedBigInteger("plant_type_id");
            $table->foreign("plant_type_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plant_name_id");
            $table->foreign("plant_name_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            $table->unsignedBigInteger("shift_id");
            $table->foreign("shift_id")->references("id")->on("shifts")->onDelete("cascade");
            $table->unsignedBigInteger("godam_id");
            $table->foreign("godam_id")->references("id")->on("godam")->onDelete("cascade");
            $table->enum('status',['running','completed']);
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
        Schema::dropIfExists('bsw_fab_sendcurtx_receivpatchvalve_entries');
    }
};
