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
        Schema::create('ccplantentry', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references("id")->on('godam')->onDelete('cascade');
            $table->string("date");
            $table->string("date_np");
            $table->string("receipt_number")->unique();
            $table->unsignedBigInteger("dana_name_id");
            $table->foreign('dana_name_id')->references('id')->on('dana_names')->onDelete('cascade');
            $table->unsignedBigInteger("dana_quantity");
            $table->string("remarks")->nullable();
            $table->enum("status",["pending","completed"])->default("pending");
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
        Schema::dropIfExists('ccplantentry');
    }
};
