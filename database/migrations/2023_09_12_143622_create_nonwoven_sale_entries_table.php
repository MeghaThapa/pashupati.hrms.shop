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
        Schema::create('nonwoven_sale_entries', function (Blueprint $table) {
            $table->id();
           
            $table->integer('stock_id');
            $table->string('fabric_roll');
            $table->string('fabric_gsm');
            $table->string('fabric_name');
            $table->string('fabric_color');
            $table->string('length');
            $table->string('gross_weight');
            $table->string('net_weight');
            $table->integer('bill_id');
            $table->integer('godam_id')->nullable();
            
            $table->enum("status",["sent","completed"])->default("sent");
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
        Schema::dropIfExists('nonwoven_sale_entries');
    }
};
