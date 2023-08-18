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
        Schema::create('double_side_laminated_fabric_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');

            $table->integer('bill_id')->nullable();
            
            $table->unsignedBigInteger('planttype_id')->nullable();
            $table->foreign('planttype_id')->references("id")->on('processing_steps')->onDelete('cascade');

            $table->unsignedBigInteger('plantname_id')->nullable();
            $table->foreign('plantname_id')->references("id")->on('processing_subcats')->onDelete('cascade');

            $table->bigInteger('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('godam')->onDelete('cascade');

            $table->integer('doublelamfabric_id')->nullable();

            // $table->bigInteger('doublelamfabric_id')->unsigned()->index()->nullable();
            // $table->foreign('doublelamfabric_id')->references('id')->on('double_side_laminated_fabrics')->onDelete('cascade');

            $table->bigInteger('doubletripalname_id')->unsigned()->index()->nullable();
            $table->foreign('doubletripalname_id')->references('id')->on('double_tripal_names')->onDelete('cascade');

            // $table->integer('doubletripalname_id')->nullable();

            
            $table->string('gram');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('roll_no');
            $table->string('loom_no')->nullable();
            $table->string('average_wt');
            $table->string('bill_number');
            $table->string('bill_date');
            $table->enum("type_lam",["single","double"])->default("single");
            $table->enum("status",["sent","pending","completed"])->default("pending");
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
        Schema::dropIfExists('double_side_laminated_fabric_stocks');
    }
};
