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
        Schema::create('rawmaterial_opening_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("rawmaterial_opening_entry_id");
            $table->foreign("rawmaterial_opening_entry_id")->references("id")->on('rawmaterial_opening_entries')->onDelete('cascade');
            $table->unsignedBigInteger("dana_group_id");
            $table->foreign("dana_group_id")->references("id")->on('dana_groups')->onDelete('cascade');
            $table->unsignedBigInteger("dana_name_id");
            $table->foreign("dana_name_id")->references("id")->on('dana_names')->onDelete('cascade');
            $table->string('qty_in_kg');
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
        Schema::dropIfExists('rawmaterial_opening_items');
    }
};
