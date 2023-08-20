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
        Schema::create('curtex_to_patch_val_fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger("fabric_group_id")->index();
            $table->foreign("fabric_group_id")->references("id")->on("fabric_groups")->onDelete("cascade");
            $table->string('standard_wt_gm');
            $table->string('fabric_type');
            $table->enum('status',['active', 'inactive'])->default('active');
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
        Schema::dropIfExists('curtex_to_patch_val_fabrics');
    }
};
