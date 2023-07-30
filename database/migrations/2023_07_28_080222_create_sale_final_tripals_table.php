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
        Schema::create('sale_final_tripals', function (Blueprint $table) {
            $table->id();
            $table->string('bill_no');
            $table->string('bill_date');
            $table->enum('bill_for',['local','export'])->default('export');
            $table->string('lorry_no');
            $table->string('gp_no');
            $table->string('do_no');
            $table->string('remarks');

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
        Schema::dropIfExists('sale_final_tripals');
    }
};
