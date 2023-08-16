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
        Schema::create('fabric_sale_entry', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->bigInteger('partyname_id')->unsigned()->index();
            $table->foreign('partyname_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->enum('bill_for',['local','export','none'])->default('none');
            $table->string('lorry_no');
            $table->string('gp_no');
            $table->string('do_no');
            $table->string('remarks');
            $table->enum("status",["pending","completed"])->default("pending");
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
        Schema::dropIfExists('fabric_sale_entry');
    }
};
