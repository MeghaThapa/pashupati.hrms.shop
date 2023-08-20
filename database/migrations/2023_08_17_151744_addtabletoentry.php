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
        Schema::table('bsw_fab_sendcurtx_receivpatchvalve_entries', function (Blueprint $table) {
            $table->string('trem_wastage')->nullable();
            $table->string('fabric_wastage')->nullable();
            $table->string('total_wastage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bsw_fab_sendcurtx_receivpatchvalve_entries', function (Blueprint $table) {
            //
        });
    }
};
