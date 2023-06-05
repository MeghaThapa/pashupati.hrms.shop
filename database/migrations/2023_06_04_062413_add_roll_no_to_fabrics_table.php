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
        Schema::table('fabrics', function (Blueprint $table) {
            $table->string('roll_no')->after('name');
            $table->string('loom_no');
            $table->string('gross_wt');
            $table->string('net_wt');
            $table->string('meter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fabrics', function (Blueprint $table) {
            //
        });
    }
};
