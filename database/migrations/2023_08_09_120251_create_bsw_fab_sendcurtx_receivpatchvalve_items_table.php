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
        Schema::create('bsw_fab_sendcurtx_receivpatchvalve_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("entry_id");
            $table->foreign("entry_id")->references("id")->on("bsw_fab_sendcurtx_receivpatchvalve_entries")->onDelete("cascade");
            $table->unsignedBigInteger("fabric_id")->index()->nullable();
            $table->foreign("fabric_id")->references("id")->on("fabrics")->onDelete("cascade");

            $table->unsignedBigInteger("printed_fabric_id")->index()->nullable();
            $table->foreign("printed_fabric_id")->references("id")->on("printed_fabrics")->onDelete("cascade");
            // $table->string('name');
            $table->string('roll_no');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('average');
            $table->string('gram_wt');
            $table->enum('status',['running','completed']);
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
        Schema::dropIfExists('bsw_fab_sendcurtx_receivpatchvalve_items');
    }
};
