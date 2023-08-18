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
        Schema::create('patch_val_dana_consumpts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("curtexToPatchValEntry_id")->index();
            $table->foreign("curtexToPatchValEntry_id")->references("id")->on("bsw_fab_sendcurtx_receivpatchvalve_entries")->onDelete("cascade");
            $table->unsignedBigInteger("godam_id")->index();
            $table->foreign("godam_id")->references("id")->on("godam")->onDelete("cascade");
            $table->unsignedBigInteger("plantType_id")->index();
            $table->foreign("plantType_id")->references("id")->on("processing_steps")->onDelete("cascade");
            $table->unsignedBigInteger("plantName_id")->index();
            $table->foreign("plantName_id")->references("id")->on("processing_subcats")->onDelete("cascade");
            $table->unsignedBigInteger("shift_id")->index();
            $table->foreign("shift_id")->references("id")->on("shifts")->onDelete("cascade");

            $table->unsignedBigInteger("dana_group_id")->index()->nullable();
            $table->foreign("dana_group_id")->references("id")->on("dana_groups")->onDelete("cascade");
            
            $table->unsignedBigInteger("dana_name_id")->index();
            $table->foreign("dana_name_id")->references("id")->on("dana_names")->onDelete("cascade");
            $table->string('quantity');
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
        Schema::dropIfExists('patch_val_dana_consumpts');
    }
};
