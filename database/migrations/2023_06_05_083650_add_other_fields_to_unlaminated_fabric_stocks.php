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
        Schema::table('unlaminated_fabric_stocks', function (Blueprint $table) {
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
        Schema::table('unlaminated_fabric_stocks', function (Blueprint $table) {
            //
        });
    }
};
