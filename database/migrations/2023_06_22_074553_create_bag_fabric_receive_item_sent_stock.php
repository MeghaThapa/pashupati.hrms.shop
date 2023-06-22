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
        Schema::create('bag_fabric_receive_item_sent_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fabric_id');
            $table->foreign("fabric_id")->references("id")->on('fabrics')->onDelete("cascade");
            $table->string('gram');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('roll_no');
            $table->string('loom_no');
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
        Schema::dropIfExists('bag_fabric_receive_item_sent_stock');
    }
};
