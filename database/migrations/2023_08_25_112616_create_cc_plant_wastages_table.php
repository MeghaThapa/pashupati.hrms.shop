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
        Schema::create('cc_plant_wastages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("ccplantentry_id");
            $table->foreign("ccplantentry_id")->references("id")->on("ccplantentry");
            
            $table->unsignedBigInteger('godam_id');
            $table->foreign("godam_id")->references("id")->on("godam");
            
            $table->unsignedBigInteger('wastage_id');
            $table->foreign("wastage_id")->references("id")->on("wastages");

            $table->unsignedBigInteger('quantity');

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
        Schema::dropIfExists('cc_plant_wastages');
    }
};
