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
        Schema::create('laminated_fabric_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fabric_id');
            $table->foreign('fabric_id')->references("id")->on('fabrics')->onDelete('cascade')->onUpdate('cascade');
            $table->string('roll_no');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('average')->nullable();
            $table->string('gram');
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on("department")->onDelete('cascade');
            $table->unsignedBigInteger('planttype_id');
            $table->foreign('planttype_id')->references('id')->on('processing_steps')->onDelete('cascade');
            $table->unsignedBigInteger('plantname_id');
            $table->foreign('plantname_id')->references('id')->on('processing_subcats')->onDelete('cascade');
            $table->string('bill_number');
            $table->string('bill_date');
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
        Schema::dropIfExists('laminated_fabric_stock');
    }
};
