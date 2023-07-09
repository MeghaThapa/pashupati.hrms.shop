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
        Schema::create('fabric_details', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number');
            $table->string('bill_date');
            $table->string('pipe_cutting');
            $table->string('bd_wastage');
            $table->string('other_wastage');
            $table->string('total_wastage');
            $table->string('total_netweight');
            $table->string('total_meter');
            $table->string('total_weightinkg');
            $table->string('total_wastageinpercent');
            $table->string('run_loom');
            $table->string('wrapping');
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
        Schema::dropIfExists('fabric_details');
    }
};
