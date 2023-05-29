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
        Schema::table('processing_steps', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->after('name');
            $table->foreign('department_id')->references("id")->on('department')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processing_steps', function (Blueprint $table) {
            //
        });
    }
};
