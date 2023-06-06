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
     Schema::create('processing_subcats', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('processing_steps_id')->index();
            $table->foreign('processing_steps_id')->references('id')->on('processing_steps');
            $table->string('name');
            $table->string('slug');
            $table->enum('status',['active','inactive']);
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
        //
    }
};
