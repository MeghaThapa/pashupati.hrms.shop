<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessingStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_steps', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references("id")->on('godam')->onDelete('cascade')->onUpdate('cascade');
            $table->string('slug');
            $table->string('code');
            $table->text('note')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('processing_steps');
    }
}
