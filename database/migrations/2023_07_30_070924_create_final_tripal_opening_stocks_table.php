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
        Schema::create('final_tripal_opening_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');

            $table->bigInteger('finaltripalname_id')->unsigned()->index();
            $table->foreign('finaltripalname_id')->references('id')->on('final_tripal_names')->onDelete('cascade');

            $table->bigInteger('godam_id')->unsigned()->index();
            $table->foreign('godam_id')->references('id')->on('godam')->onDelete('cascade');
            
            $table->string('roll_no');
            $table->string('gram');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('average');
            $table->string('bill_number');
            $table->string('bill_date');
            $table->enum("type",["opening","transaction"])->default("opening");
            $table->string('date_en');
            $table->string('date_np');
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
        Schema::dropIfExists('final_tripal_opening_stocks');
    }
};
