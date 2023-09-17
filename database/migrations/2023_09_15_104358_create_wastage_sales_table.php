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
        Schema::create('wastage_sales', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->bigInteger('partyname_id')->unsigned()->index();
            $table->foreign('partyname_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->string('lorry_no');
            $table->string('gp_no');
            $table->string('do_no');
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
        Schema::dropIfExists('wastage_sales');
    }
};
