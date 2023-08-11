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
        Schema::create('opening_storein_reports', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->unsignedBigInteger("name")->index();
            $table->foreign("name")->references("id")->on("items_of_storeins")->onDelete("cascade");
            $table->string('quantity');
            $table->string('rate');
            $table->string('total');
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
        Schema::dropIfExists('opening_storein_reports');
    }
};
