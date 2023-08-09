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
        Schema::create('final_tripals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('doublefabric_id');
            $table->foreign('doublefabric_id')->references("id")->on('double_side_laminated_fabric_stocks')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('fabric_id')->nullable();
            $table->foreign('fabric_id')->references("id")->on('fabrics')->onDelete('cascade')->onUpdate('cascade');
            
            $table->unsignedBigInteger('planttype_id')->nullable();
            $table->foreign('planttype_id')->references("id")->on('processing_steps')->onDelete('cascade');

            $table->unsignedBigInteger('plantname_id')->nullable();
            $table->foreign('plantname_id')->references("id")->on('processing_subcats')->onDelete('cascade');

            $table->bigInteger('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('godam')->onDelete('cascade');
            $table->bigInteger('finaltripalname_id')->unsigned()->index();
            $table->foreign('finaltripalname_id')->references('id')->on('final_tripal_names')->onDelete('cascade');
            
            $table->string('gram');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
            $table->string('roll_no');
            $table->string('loom_no');
            $table->string('average_wt');
            $table->string('bill_number');
            $table->string('bill_date');
            $table->enum("status",["sent","pending","completed"])->default("pending");
            $table->string('date_en');
            $table->string('date_np');
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
        Schema::dropIfExists('final_tripals');
    }
};
