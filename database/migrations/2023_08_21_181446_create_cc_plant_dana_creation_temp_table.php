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
        Schema::create('cc_plant_dana_creation_temp', function (Blueprint $table) {
            $table->id();
            $table->string("dananame");
            $table->unsignedBigInteger("danagroup_id");
            $table->foreign("danagroup_id")->references("id")->on("dana_groups")->onDelete("cascade");
            $table->unsignedBigInteger("entry_id");
            $table->foreign("entry_id")->references("id")->on("ccplantentry")->onDelete("cascade");
            $table->string("quantity");
            $table->unsignedBigInteger("planttype_id");
            $table->foreign("planttype_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plantname_id");
            $table->foreign("plantname_id")->references("id")->on("processing_subcats")->onDelete("cascade");
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
        Schema::dropIfExists('cc_plant_dana_creation_temp');
    }
};
