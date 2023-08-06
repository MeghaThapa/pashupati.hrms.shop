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
        Schema::create('bsw_sent_lam_fabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bsw_lam_fab_for_printing_entry_id");
            $table->foreign("bsw_lam_fab_for_printing_entry_id")->references("id")->on('bsw_lam_fab_for_printing_entries')->onDelete('cascade');
            $table->unsignedBigInteger("fabric_id");
            $table->foreign("fabric_id")->references("id")->on('fabrics')->onDelete('cascade');
            $table->string('roll_no');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('gram_wt');
            $table->string('meter');
            $table->string('average');
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
        Schema::dropIfExists('bsw_sent_lam_fabs');
    }
};
