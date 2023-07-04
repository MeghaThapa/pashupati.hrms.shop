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
        Schema::create('bag_temporary_fabric_receive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("fabric_bag_entry_id");
            $table-> foreign("fabric_bag_entry_id")->references("id")->on('bag_fabric_entry')->onDelete('cascade');
            $table->unsignedBigInteger('fabric_id');
            $table->foreign("fabric_id")->references("id")->on('fabrics');
            $table->string('average');
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
        Schema::dropIfExists('fabric_receive_for_bag_temporary');
    }
};
