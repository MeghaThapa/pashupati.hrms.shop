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
        Schema::create('fabric_non_woven_receive_entry_stocks', function (Blueprint $table) {
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
            $table->string('bill_id')->nullable();
            // $table->string('dana_quantity');
            $table->unsignedBigInteger('nonfabric_id');
            $table->foreign('nonfabric_id')->references("id")->on('fabric_non_woven_recive_entries')->onDelete('cascade')->onUpdate('cascade');
            $table->enum("status",["sent","pending","completed"])->default("sent");
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
        Schema::dropIfExists('fabric_non_woven_receive_entry_stocks');
    }
};
