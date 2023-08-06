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
        Schema::create('bsw_lam_printed_fabric_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bswfabEntry_id");
            $table->foreign("bswfabEntry_id")->references("id")->on('bsw_lam_fab_for_printing_entries')->onDelete('cascade');
            $table->unsignedBigInteger("printed_fabric_id");
            $table->foreign("printed_fabric_id")->references("id")->on('printed_fabrics')->onDelete('cascade');
            $table->string('roll_no');
            $table->string('gross_weight');
            $table->string('net_weight');
            $table->string('meter');
            $table->string('average');
            $table->string('gram_weight');
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
        Schema::dropIfExists('bsw_lam_printed_fabric_stocks');
    }
};
