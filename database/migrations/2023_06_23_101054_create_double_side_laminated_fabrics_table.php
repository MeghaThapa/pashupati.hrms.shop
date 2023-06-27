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
        Schema::create('double_side_laminated_fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            
            $table->bigInteger('fabric_id')->unsigned()->index();
            $table->foreign('fabric_id')->references('id')->on('fabrics')->onDelete('cascade');

            $table->unsignedBigInteger('planttype_id');
            $table->foreign('planttype_id')->references("id")->on('processing_steps')->onDelete('cascade');

            $table->unsignedBigInteger('plantname_id');
            $table->foreign('plantname_id')->references("id")->on('processing_subcats')->onDelete('cascade');

            $table->bigInteger('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('godam')->onDelete('cascade');

            $table->string('gram');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('roll_no');
            $table->string('loom_no');
            $table->string('average_wt');
            $table->string('bill_number');
            $table->string('bill_date');
            $table->enum("type_lam",["single","double"])->default("single");
            $table->enum("status",["sent","pending"])->default("pending");
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
        Schema::dropIfExists('double_side_laminated_fabrics');
    }
};
