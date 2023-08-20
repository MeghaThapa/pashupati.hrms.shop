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
         Schema::create('storeout', function (Blueprint $table) {
                $table->id();
                $table->date('receipt_date');
                $table->string('receipt_no');
                $table->unsignedBigInteger('godam_id'); //Type
                $table->foreign('godam_id')->references('id')->on('godam');
                $table->string('total_amount')->default('0');
                $table->string('remark')->nullable();
                $table->enum('status', ['running', 'completed']);
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
