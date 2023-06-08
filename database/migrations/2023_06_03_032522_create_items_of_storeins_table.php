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
        Schema::create('items_of_storeins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('pnumber');
            $table->bigInteger('size_id')->unsigned()->index();
            $table->foreign('size_id')->references('id')->on('sizes');

             $table->bigInteger('unit_id')->unsigned()->index();
            $table->foreign('unit_id')->references('id')->on('units');

            $table->bigInteger('department_id')->unsigned()->index();
            $table->foreign('department_id')->references('id')->on('storein_departments');
            $table->bigInteger('category_id')->unsigned()->index();
            $table->foreign('category_id')->references('id')->on('storein_categories');
            $table->enum('status',['active','inactive']);
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
        Schema::dropIfExists('items_of_storeins');
    }
};
