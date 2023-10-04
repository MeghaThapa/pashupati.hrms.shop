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
        Schema::create('prints_cutsbag_extra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("printAndCutEntry_id");
            $table->foreign("printAndCutEntry_id")->references("id")->on('printed_and_cutted_rolls_entry')->onDelete('cascade');
            $table->unsignedBigInteger("group_id");
            $table->foreign("group_id")->references("id")->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger("bag_brand_id");
            $table->foreign("bag_brand_id")->references("id")->on('bag_brands')->onDelete('cascade');
            $table->string('quantity_piece');
            $table->string('average');
            $table->string('wastage');
            $table->string('roll_no');
            $table->unsignedBigInteger("fabric_id");
            $table->foreign("fabric_id")->references("id")->on('fabrics')->onDelete('cascade');
            $table->string('net_weight');
            $table->string('cut_length');
            $table->string('gross_weight');
            $table->string('meter');
            $table->string('avg');
            $table->string('req_bag');
            $table->unsignedBigInteger("godam_id");
            $table->foreign("godam_id")->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger("wastage_id");
            $table->foreign("wastage_id")->references("id")->on('wastages')->onDelete('cascade');
            // Define a custom name for the foreign key constraint
            $table->unsignedBigInteger("bag_fabric_receive_item_sent_stock_id")->nullable();
            $table->foreign("bag_fabric_receive_item_sent_stock_id")->references("id")->on('bag_fabric_receive_item_sent_stock')->onDelete('cascade');


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
        Schema::dropIfExists('prints_cutsbag_extra');
    }
};
