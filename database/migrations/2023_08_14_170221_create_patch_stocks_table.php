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
        Schema::create('patch_stocks', function (Blueprint $table) {
            $table->id();
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
        Schema::dropIfExists('patch_stocks');
    }
};
