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
        Schema::create('storein', function (Blueprint $table) {
            $table->id();
            $table->string('sr_no')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('pp_no')->nullable();
            $table->date('purchase_date');
            $table->unsignedBigInteger('storein_type_id'); //Type
            $table->foreign('storein_type_id')->references('id')->on('storein_types');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('sub_total')->default('0');
            $table->string('total_discount')->default('0');
            $table->string('discount_percent')->default('0');
            $table->string('grand_total')->default('0');
            $table->longText('extra_charges')->nullable();
            $table->string('image_path')->nullable();
            $table->longText('note')->nullable();
            $table->enum('status', ['running', 'completed']);
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
        Schema::dropIfExists('admin_storein');
    }
};
