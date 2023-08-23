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
        Schema::create('reprocess_waste_temps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("reprocess_waste_id");
            $table->foreign("reprocess_waste_id")->references("id")->on("reprocess_wastes");
            $table->unsignedBigInteger("planttype_id");
            $table->foreign("planttype_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plantname_id");
            $table->foreign("plantname_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            $table->unsignedBigInteger("dana_id");
            $table->foreign("dana_id")->references("id")->on("dana_names")->onDelete("cascade");
            $table->unsignedBigInteger("waste_id");
            $table->foreign("waste_id")->references("id")->on("wastages")->onDelete("cascade");
            $table->string("quantity");
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
        Schema::dropIfExists('reprocess_waste_temps');
    }
};
