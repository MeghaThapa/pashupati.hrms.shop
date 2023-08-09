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
        Schema::create('sale_final_tripal_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('roll');
            $table->string('gross');
            $table->string('net');
            $table->string('meter');
            $table->string('average');
            $table->string('gram');
            $table->string('bill_no');
            $table->string('bill_date');
            $table->unsignedBigInteger('salefinal_id');
            $table->foreign('salefinal_id')->references("id")->on('sale_final_tripals')->onDelete('cascade');
            $table->unsignedBigInteger('finaltripal_id');
            $table->foreign('finaltripal_id')->references("id")->on('final_tripal_stocks')->onDelete('cascade');
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
        Schema::dropIfExists('sale_final_tripal_lists');
    }
};
