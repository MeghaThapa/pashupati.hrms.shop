<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferredProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transferred_products', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('finished_id')->unsigned()->index();
            $table->foreign('finished_id')->references('id')->on('finished_products')->onDelete('cascade');

            $table->bigInteger('showroom_id')->unsigned()->index()->nullable();
            $table->foreign('showroom_id')->references('id')->on('showrooms')->onDelete('set null');

            $table->string('transferred_code');
            $table->string('slug');

            $table->date('transferred_date');
            $table->string('cartoon_number')->nullable();
            $table->string('transferred_quantities')->nullable();
            $table->string('transferred_image');
            $table->text('note')->nullable();
            $table->boolean('status')->nullable()->default(1);

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
        Schema::dropIfExists('transferred_products');
    }
}
