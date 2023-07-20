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
        Schema::create('fabric_non_woven_recive_entries', function (Blueprint $table) {
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

            $table->unsignedBigInteger('planttype_id');
            $table->foreign('planttype_id')->references("id")->on('processing_steps')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('plantname_id');
            $table->foreign('plantname_id')->references("id")->on('processing_subcats')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('shift_id');
            $table->foreign('shift_id')->references("id")->on('shifts')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('nonwovenfabric_id');
            $table->foreign('nonwovenfabric_id')->references("id")->on('non_woven_fabrics')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('fabric_non_woven_recive_entries');
    }
};
