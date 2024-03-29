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
        Schema::create('fabric_godam_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->string('name');
            $table->string('slug');
            $table->string('roll');
            $table->string('net_wt');
            $table->unsignedBigInteger('fabricgodam_id');
            $table->foreign('fabricgodam_id')->references("id")->on('fabric_godams')->onDelete('cascade');
            $table->unsignedBigInteger('fromgodam_id');
            $table->foreign('fromgodam_id')->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger('togodam_id');
            $table->foreign('togodam_id')->references("id")->on('godam')->onDelete('cascade');
            // $table->unsignedBigInteger('fabricstock_id')->nullable();
            // $table->foreign('fabricstock_id')->references("id")->on('fabric_stock')->onDelete('cascade');
           
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
        Schema::dropIfExists('fabric_godam_transfers');
    }
};
