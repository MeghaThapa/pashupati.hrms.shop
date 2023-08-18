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
        Schema::create('double_tripal_dana_consumptions', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_id')->nullable();
            $table->string("bill_no");
            $table->unsignedBigInteger("autoloader_id");
            $table-> foreign("autoloader_id")->references("id")->on('autoload_items_stock')->onDelete('cascade');
            $table->unsignedBigInteger("dana_name_id");
            $table-> foreign("dana_name_id")->references("id")->on('dana_names')->onDelete('cascade');
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
        Schema::dropIfExists('double_tripal_dana_consumptions');
    }
};
