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
        Schema::create('cc_plant_items_temp', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("cc_plant_entry_id");
            $table->foreign("cc_plant_entry_id")->references("id")->on("ccplantentry");
            $table->unsignedBigInteger("planttype_id");
            $table->foreign("planttype_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plantname_id");
            $table->foreign("plantname_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            $table->unsignedBigInteger("dana_id");
            $table->foreign("dana_id")->references("id")->on("dana_names")->onDelete("cascade");
            $table->string("quantity");
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
        Schema::dropIfExists('cc_plant_items_temp');
    }
};
