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
        Schema::create('printing_and_cutting_bag_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("printAndCutEntry_id");
            $table-> foreign("printAndCutEntry_id")->references("id")->on('printed_and_cutted_rolls_entry')->onDelete('cascade');
            $table->unsignedBigInteger("group_id");
            $table-> foreign("group_id")->references("id")->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger("bag_brand_id");
            $table-> foreign("bag_brand_id")->references("id")->on('bag_brands')->onDelete('cascade');
            $table->string('quantity_piece');
            $table->string('average');
            $table->string('wastage');
            $table->string('roll_no');
            $table->unsignedBigInteger("fabric_id");
            $table-> foreign("fabric_id")->references("id")->on('fabrics')->onDelete('cascade');
            $table->string('net_weight');
            $table->string('cut_length');
            $table->string('qty_in_kg');
            $table->string('gross_weight');
            $table->string('meter');
            $table->string('avg');
            $table->string('req_bag');
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
        Schema::dropIfExists('printing_and_cutting_bag_items');
    }
};
