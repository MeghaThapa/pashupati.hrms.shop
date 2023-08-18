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
        Schema::create('fabric_send_and_receive_entry', function (Blueprint $table) {
            $table->id();
            $table->string("bill_number")->unique();
            $table->string("bill_date");
            $table->string("bill_date_np");
            $table->unsignedBigInteger("godam_id");
            $table->foreign("godam_id")->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger("planttype_id");
            $table->foreign("planttype_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plantname_id");
            $table->foreign("plantname_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            $table->unsignedBigInteger("shift_id");
            $table->foreign("shift_id")->references("id")->on("shifts")->onDelete("cascade");
            $table->string("remarks")->nullable();
            $table->enum("status",["pending","completed"])->default("pending");
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
        Schema::dropIfExists('fabric_send_and_receive_entry');
    }
};
