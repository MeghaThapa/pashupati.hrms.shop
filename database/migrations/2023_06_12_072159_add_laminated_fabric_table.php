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
        Schema::table('laminated_fabric', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on("department")->onDelete('cascade');
            $table->unsignedBigInteger('planttype_id');
            $table->foreign('planttype_id')->references('id')->on('processing_steps')->onDelete('cascade');
            $table->unsignedBigInteger('plantname_id');
            $table->foreign('plantname_id')->references('id')->on('processing_subcats')->onDelete('cascade');
            $table->string('bill_number');
            $table->string('bill_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('laminated_fabric', function (Blueprint $table) {
            //
        });
    }
};
