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
        Schema::create('prints_and_cuts_dana_consumptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("printCutEntry_id");
            $table-> foreign("printCutEntry_id")->references("id")->on('printed_and_cutted_rolls_entry')->onDelete('cascade');
            $table->unsignedBigInteger("godam_id");
            $table-> foreign("godam_id")->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger("dana_group_id");
            $table-> foreign("dana_group_id")->references("id")->on('dana_groups')->onDelete('cascade');
            $table->unsignedBigInteger("dana_name_id");
            $table-> foreign("dana_name_id")->references("id")->on('dana_names')->onDelete('cascade');
            $table->string('quantity');
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
        Schema::dropIfExists('prints_and_cuts_dana_consumptions');
    }
};
