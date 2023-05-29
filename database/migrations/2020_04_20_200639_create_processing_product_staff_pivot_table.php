<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProcessingProductStaffPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processing_product_staff', function (Blueprint $table) {
            $table->bigInteger('processing_product_id')->unsigned()->index();
            $table->foreign('processing_product_id')->references('id')->on('processing_products')->onDelete('cascade');

            $table->bigInteger('staff_id')->unsigned()->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('set null');

            $table->bigInteger('processing_step_id')->unsigned()->index();
            $table->foreign('processing_step_id')->references('id')->on('processing_steps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processing_product_staff');
    }
}
