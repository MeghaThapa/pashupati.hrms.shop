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
        Schema::table('fabric_non_woven_recive_entries', function (Blueprint $table) {
            $table->unsignedBigInteger('nonwovenfabric_id');
            $table->foreign('nonwovenfabric_id')->references("id")->on('non_woven_fabrics')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_non_woven_recive_entries', function (Blueprint $table) {
            //
        });
    }
};
