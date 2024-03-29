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
        Schema::create('bag_bundle_openings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("group_id");
            $table-> foreign("group_id")->references("id")->on('groups')->onDelete('cascade');
            $table->unsignedBigInteger("bag_brand_id");
            $table-> foreign("bag_brand_id")->references("id")->on('bag_brands')->onDelete('cascade');
            $table->string('bundle_no');
            $table->string('qty_pcs');
            $table->string('qty_in_kg');
            $table->string('average_weight');
            $table->enum('type',['transaction','opening'])->default('transaction');
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
        Schema::dropIfExists('bag_bundle_openings');
    }
};
