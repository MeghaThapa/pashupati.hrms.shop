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
           Schema::create('tape_entry_stock', function (Blueprint $table) {
                $table->id();

                // $table->unsignedBigInteger('tape_entry_id');
                // $table->foreign('tape_entry_id')->references("id")->on('tape_entry')->onDelete('cascade')->onUpdate('cascade');

                $table->unsignedBigInteger('toGodam_id');
                $table->foreign('toGodam_id')->references("id")->on('godam')->onDelete('cascade')->onUpdate('cascade');

                // $table->unsignedBigInteger('plantType_id');
                // $table->foreign('plantType_id')->references("id")->on('processing_steps')->onDelete('cascade')->onUpdate('cascade');

                // $table->unsignedBigInteger('plantName_id');
                // $table->foreign('plantName_id')->references("id")->on('processing_subcats')->onDelete('cascade')->onUpdate('cascade');

                // $table->unsignedBigInteger('shift_id');
                // $table->foreign('shift_id')->references("id")->on('shifts')->onDelete('cascade')->onUpdate('cascade');

                $table->string('tape_type');
                $table->string('tape_qty_in_kg');
                $table->string('total_in_kg');
                $table->string('loading');
                $table->string('running');
                $table->string('bypass_wast');
                $table->enum("cause",["opening","transaction"])->default('transaction');
                // $table->string('dana_in_kg');

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
