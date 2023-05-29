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
        Schema::create('raw_material_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raw_material_id')->index();
            $table->foreign('raw_material_id')->references('id')->on('raw_materials');
            $table->unsignedBigInteger('storein_type_id')->index();
            $table->foreign('storein_type_id')->references('id')->on('storein');
            $table->unsignedBigInteger('from_godam_id')->index();
            $table->foreign('from_godam_id')->references('id')->on('godams')->nullable();
            $table->string('challan_no')->nullable();
            $table->string('gp_no')->nullable();
            $table->unsignedBigInteger('to_godam_id')->index();
            $table->foreign('to_godam_id')->references('id')->on('godams')->nullable();
            $table->string('receipt_no');
            $table->string('remark')->nullable();
            $table->string('lorry_no');
            $table->unsignedBigInteger('dana_group_id')->index();
            $table->foreign('dana_group_id')->references('id')->on('dana_groups');
            $table->unsignedBigInteger('dana_name_id')->index();
            $table->foreign('dana_name_id')->references('id')->on('dana_names');
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
        Schema::dropIfExists('raw_material_items');
    }
};
