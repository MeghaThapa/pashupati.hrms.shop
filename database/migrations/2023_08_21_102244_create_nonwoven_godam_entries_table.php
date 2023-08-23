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
        Schema::create('nonwoven_godam_entries', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->string('roll');
            $table->string('gsm');
            $table->string('name');
            $table->string('slug');
            $table->string('color');
            $table->string('length');
            $table->string('gross');
            $table->string('net');
            $table->integer('stock_id');
            
            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger('nonwovengodam_id');
            $table->foreign('nonwovengodam_id')->references("id")->on('nonwoven_godams')->onDelete('cascade');
            
            $table->enum("status",["sent","completed"])->default("sent");
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
        Schema::dropIfExists('nonwoven_godam_entries');
    }
};
