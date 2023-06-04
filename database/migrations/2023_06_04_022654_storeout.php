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
                $table->unsignedBigInteger('for'); //Type
                $table->foreign('for')->references('id')->on('storein_departments');
                $table->string('total_amount')->default('0');
                $table->string('remark')->nullable();
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
