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
        Schema::table('raw_materials', function (Blueprint $table) {
            $table->unsignedBigInteger('storein_type_id');
            $table->foreign('storein_type_id')->references('id')->on('storein_types')->onDelete('cascade')->onDelete('cascade');
            $table->string('receipt_no');
            $table->unsignedBigInteger('from_godam_id')->nullable();
            $table->foreign('from_godam_id')->references('id')->on('department')->onDelete('cascade')->onUpdate('cascade');
            $table->string('challan_no')->nullable();
            $table->string('gp_no')->nullable();
            $table->unsignedBigInteger('to_godam_id')->nullable();
            $table->foreign('to_godam_id')->references('id')->on('department')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('raw_materials', function (Blueprint $table) {
            //
        });
    }
};
