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
        Schema::create('reprocess_wastage_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reprocess_waste_id');
            $table->foreign("reprocess_waste_id")->references("id")->on("reprocess_wastes");

            $table->unsignedBigInteger('dye_quantity');
            $table->unsignedBigInteger('cutter_quantity');
            $table->unsignedBigInteger('melt_quantity');

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
        Schema::dropIfExists('reprocess_wastage_details');
    }
};
