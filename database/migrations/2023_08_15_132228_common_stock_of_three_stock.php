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
         Schema::create('common_stock_of_three_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("curtexToPatchValEntry_id")->index();
            $table->foreign("curtexToPatchValEntry_id")->references("id")->on("bsw_fab_sendcurtx_receivpatchvalve_entries")->onDelete("cascade");
            $table->unsignedBigInteger("curtexToPatchValFabric_id")->index();
            $table->foreign("curtexToPatchValFabric_id")->references("id")->on("curtex_to_patch_val_fabrics")->onDelete("cascade");
            $table->string('roll_no');
            $table->string('gross_weight');
            $table->string('net_weight');
            $table->string('meter');
            $table->string('avg');
            $table->string('gram_weight')->nullable();
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
        //
    }
};
