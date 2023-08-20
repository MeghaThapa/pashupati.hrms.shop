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
        Schema::table('fabric_stock', function (Blueprint $table) {
            $table->bigInteger('curtexToPatchValFabric_id')->unsigned()->nullable()->index();
            $table->foreign('curtexToPatchValFabric_id')->references('id')->on('curtex_to_patch_val_fabrics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabric_stock', function (Blueprint $table) {
            //
        });
    }
};
