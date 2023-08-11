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
        Schema::create('temp_dana_consumption_table_fsr', function (Blueprint $table) {
            $table->id();
            $table->string("bill_number");
            $table->unsignedBigInteger("dana_name_id");
            $table->foreign('dana_name_id')->references("id")->on("dana_names")->onDelete("cascade");
            $table->unsignedBigInteger("dana_group_id");
            $table->foreign('dana_group_id')->references("id")->on("dana_groups")->onDelete("cascade");
            $table->string("consumption_quantity");
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
        Schema::dropIfExists('temp_dana_consumption_table_fsr');
    }
};
