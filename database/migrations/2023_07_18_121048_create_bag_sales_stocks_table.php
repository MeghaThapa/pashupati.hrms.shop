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
        Schema::create('bag_sales_stocks', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger("group_id");
            $table->foreign("group_id")->references("id")->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger("brand_bag_id");
            $table->foreign("brand_bag_id")->references("id")->on('bag_brands')->onDelete('cascade');
            $table->string('bundel_no');
            $table->string('pcs');
            $table->string('weight');
            $table->string('average');
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
        Schema::dropIfExists('bag_sales_stocks');
    }
};
