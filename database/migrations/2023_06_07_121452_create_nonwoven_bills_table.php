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
        Schema::create('nonwoven_bills', function (Blueprint $table) {
            $table->id();
            $table->string("bill_no");
            $table->string("bill_date");
            $table->unsignedBigInteger("godam_id");
            $table-> foreign("godam_id")->references("id")->on('godam')->onDelete('cascade');

            $table->unsignedBigInteger('planttype_id')->nullable();
            $table->foreign('planttype_id')->references("id")->on('processing_steps')->onDelete('cascade');

            $table->unsignedBigInteger('plantname_id')->nullable();
            $table->foreign('plantname_id')->references("id")->on('processing_subcats')->onDelete('cascade');

            $table->bigInteger('shift_id')->unsigned()->index();
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
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
        Schema::dropIfExists('nonwoven_bills');
    }
};
