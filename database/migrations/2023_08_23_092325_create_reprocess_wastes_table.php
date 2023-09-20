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
        Schema::create('reprocess_wastes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')->references("id")->on('godam')->onDelete('cascade');
            $table->string("date");
            $table->string("receipt_number")->unique();
            $table->string("remarks")->nullable();
            $table->enum("status",["Running","completed"])->default("Running");
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
        Schema::dropIfExists('reprocess_wastes');
    }
};
