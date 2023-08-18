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
        Schema::create('unlaminatedfabrictripals', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number');
            $table->string('bill_date');
            $table->integer('bill_id')->nullable();
            
            $table->unsignedBigInteger('fabric_id')->nullable();
            $table->foreign('fabric_id')->references("id")->on('fabrics')->onDelete('cascade')->onUpdate('cascade');
            $table->string('roll_no');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('average')->nullable();
            $table->string('gram');
            $table->enum("status",['pending',"sent","completed"])->default("pending");
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on("godam")->onDelete('cascade');
            $table->unsignedBigInteger('planttype_id')->nullable();
            $table->foreign('planttype_id')->references('id')->on('processing_steps')->onDelete('cascade');
            $table->unsignedBigInteger('plantname_id')->nullable();
            $table->foreign('plantname_id')->references('id')->on('processing_subcats')->onDelete('cascade');
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
        Schema::dropIfExists('unlaminatedfabrictripals');
    }
};
