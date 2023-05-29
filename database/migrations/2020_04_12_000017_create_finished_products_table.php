<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finished_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('porcessing_pro_id')->unsigned()->index();
            $table->foreign('porcessing_pro_id')->references('id')->on('processing_products')->onDelete('cascade');
            $table->bigInteger('sub_cat_id')->unsigned()->index();
            $table->foreign('sub_cat_id')->references('id')->on('sub_categories')->onDelete('cascade');
            $table->string('finished_code');
            $table->string('slug');
            $table->string('sizes');
            $table->string('rejected_quantities')->nullable();
            $table->string('quantities');
            $table->date('finished_date');
            $table->string('finished_image')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('finished_products');
    }
}