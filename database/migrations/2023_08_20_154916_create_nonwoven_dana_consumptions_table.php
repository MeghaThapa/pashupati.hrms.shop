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
        Schema::create('nonwoven_dana_consumptions', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_id')->nullable();
            $table->integer('autoloader_id')->nullable();
            $table->string("bill_no");
            $table->integer("from_godam_id");
            $table->integer("plant_type_id");
            $table->integer("plant_name_id");
            $table->integer("shift_id");
            $table->integer("dana_group_id");
            $table->integer("dana_name_id");
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
        Schema::dropIfExists('nonwoven_dana_consumptions');
    }
};
