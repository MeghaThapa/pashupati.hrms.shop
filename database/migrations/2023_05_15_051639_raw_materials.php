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
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedBigInteger('storein_type_id')->index();
            $table->foreign('storein_type_id')->references('id')->on('storein_types');

            $table->unsignedBigInteger('to_godam_id')->index();
            $table->foreign('to_godam_id')->references('id')->on('godam');

            //if type is godam
            // $table->unsignedBigInteger('from_godam_id')->index();
            // $table->foreign('from_godam_id')->references('id')->on('godam')->nullable();

            $table->unsignedBigInteger('from_godam_id')->index()->nullable()->default(NULL);
            $table->foreign('from_godam_id')->references('id')->on('godam')->nullable();


            $table->string('challan_no')->nullable();
            $table->string('gp_no')->nullable();

            $table->string('date');
            $table->string('pp_no');
            $table->string('receipt_no');

            $table->enum('status', ['pending', 'complete', 'cancel']);

            $table->string('remark');
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
        //
    }
};
