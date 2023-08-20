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
        Schema::create('tripal_godam_entries', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->string('name');
            $table->string('slug');
            $table->string('roll');
            $table->string('gram');
            $table->string('gross');
            $table->string('net');
            $table->string('meter');
            $table->string('average');
            $table->string('gsm');
            $table->integer('stock_id');
            $table->unsignedBigInteger('finaltripal_id');
            $table->foreign('finaltripal_id')->references("id")->on('final_tripal_names')->onDelete('cascade');

            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger('tripalgodam_id');
            $table->foreign('tripalgodam_id')->references("id")->on('tripal_godams')->onDelete('cascade');
           
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
        Schema::dropIfExists('tripal_godam_entries');
    }
};
