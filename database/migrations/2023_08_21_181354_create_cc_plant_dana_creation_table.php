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
        Schema::create('cc_plant_dana_creation', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger("dana_name_id");
            $table->foreign("dana_name_id")->references("id")->on("dana_names")->onDelete("cascade");
            
            $table->unsignedBigInteger("dana_group_id");
            $table->foreign("dana_group_id")->references("id")->on("dana_groups")->onDelete("cascade");

            $table->unsignedBigInteger("cc_plant_entry_id");
            $table->foreign("cc_plant_entry_id")->references("id")->on("ccplantentry")->onDelete("cascade");

            
            $table->unsignedBigInteger("plant_type_id");
            $table->foreign("plant_type_id")->references("id")->on("processing_steps")->onDelete("cascade");
            
            $table->unsignedBigInteger("plant_name_id");
            $table->foreign("plant_name_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            
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
        Schema::dropIfExists('cc_plant_dana_creation');
    }
};
