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
        Schema::create('bag_bundel_items', function (Blueprint $table) {
            $table->id();
            $table->string('bundel_no');

            $table->unsignedBigInteger("bag_bundel_entry_id");
            $table->foreign("bag_bundel_entry_id")->references("id")->on('bag_bundel_entries')->onDelete('cascade');
            $table->unsignedBigInteger("group_id");
            $table->foreign("group_id")->references("id")->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger("bag_brand_id");
            $table->foreign("bag_brand_id")->references("id")->on('bag_brands')->onDelete('cascade');
            $table->string('qty_in_kg');
            $table->string('qty_pcs');
            $table->string('average_weight');
            $table->enum('status', ['sent', 'completed']);
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
        Schema::dropIfExists('bag_bundel_items');
    }
};
