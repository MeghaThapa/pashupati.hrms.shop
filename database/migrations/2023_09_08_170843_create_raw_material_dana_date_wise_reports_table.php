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
        Schema::create('raw_material_dana_date_wise_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dana_name_id');
            $table->foreign('dana_name_id')
                ->references('id')
                ->on('dana_names')
                ->onDelete('cascade');
            $table->unsignedBigInteger('godam_id');
            $table->foreign('godam_id')
                ->references('id')
                ->on('godam')
                ->onDelete('cascade');
            $table->date('date');
            $table->decimal('opening_amount',10,2);
            $table->decimal('import',10,2);
            $table->decimal('local',10,2);
            $table->decimal('from_godam',10,2);
            $table->decimal('tape',10,2);
            $table->decimal('lam',10,2);
            $table->decimal('nw_plant',10,2);
            $table->decimal('sales',10,2);
            $table->decimal('to_godam',10,2);
            $table->decimal('closing',10,2);
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
        Schema::dropIfExists('raw_material_dana_date_wise_reports');
    }
};
