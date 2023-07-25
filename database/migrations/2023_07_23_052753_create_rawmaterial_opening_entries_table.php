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
        Schema::create('rawmaterial_opening_entries', function (Blueprint $table) {
            $table->id();
            $table->string('opening_date');
            $table->string('receipt_no');
            $table->string('to_godam');
            $table->string('remark')->nullable();
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
        Schema::dropIfExists('rawmaterial_opening_entries');
    }
};
