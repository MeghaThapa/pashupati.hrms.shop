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
        Schema::create('sauda_items', function (Blueprint $table) {
            $table->id();
            $table->string('sauda_no');
            $table->date('sauda_date');
            $table->enum('sauda_for',['Export','Local']);
            $table->unsignedBigInteger('supplier_id');
            $table->string('acc_name');
            $table->unsignedBigInteger('delivery_order_for_item_id');
            $table->text('fabric_name')->nullable();
            $table->decimal('qty', 10, 2);
            $table->decimal('order_qty', 10, 2);
            $table->enum('unit_name',['Pcs','Kgs']);
            $table->decimal('rate', 10, 2);
            $table->text('remarks')->nullable();
            $table->enum('status',['Fresh','Dispatched'])->default('Fresh');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('delivery_order_for_item_id')->references('id')->on('delivery_order_for_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sauda_items');
    }
};
