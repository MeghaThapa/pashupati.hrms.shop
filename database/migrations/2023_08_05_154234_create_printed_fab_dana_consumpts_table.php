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
        Schema::create('printed_fab_dana_consumpts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bswfabEntry_id");
            $table->foreign("bswfabEntry_id")->references("id")->on('bsw_lam_fab_for_printing_entries')->onDelete('cascade');
            $table->unsignedBigInteger("godam_id");
            $table->foreign("godam_id")->references("id")->on('godam')->onDelete('cascade');
            $table->unsignedBigInteger("dana_name_id");
            $table->foreign("dana_name_id")->references("id")->on('dana_names')->onDelete('cascade');
            $table->string('quantity');
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
        Schema::dropIfExists('printed_fab_dana_consumpts');
    }
};
