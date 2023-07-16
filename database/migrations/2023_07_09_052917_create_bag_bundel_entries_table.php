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
        Schema::create('bag_bundel_entries', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no');
            $table->string('receipt_date');
            $table->string('nepali_date');
            $table->string('total_bundle_quantity')->default('0');
            $table->enum('status',['running','completed']);
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
        Schema::dropIfExists('bag_bundel_entries');
    }
};
