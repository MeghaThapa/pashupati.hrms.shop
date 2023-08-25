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
        Schema::create('tripal_godams', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('fromgodam_id');
            $table->foreign('fromgodam_id')->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger('togodam_id');
            $table->foreign('togodam_id')->references("id")->on('godam')->onDelete('cascade');
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
        Schema::dropIfExists('tripal_godams');
    }
};
