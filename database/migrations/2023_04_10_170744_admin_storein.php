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
        Schema::create('admin_storein', function (Blueprint $table) {
            $table->id();
            $table->string('sr_no');
            $table->string('bill_no');
            $table->string('pp_no');
            $table->date('purchase_date');
            $table->unsignedBigInteger('storein_id'); //Type
            $table->foreign('storein_id')->references('id')->on('storein');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->string('sub_total')->default('0');
            $table->string('total_discount')->default('0');
            $table->string('transport_cost')->default('0');
            $table->string('grand_total')->default('0');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('tax');
            $table->string('paid_amount')->default('0');
            $table->string('due_amount')->default('0');
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->string('image_path')->nullable();
            $table->longText('note')->nullable();
            $table->enum('status', ['active', 'inactive']);
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
