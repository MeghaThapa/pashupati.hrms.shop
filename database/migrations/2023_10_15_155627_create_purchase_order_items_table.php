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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->string('item_name');
            $table->unsignedBigInteger('storein_department_id');
            $table->unsignedBigInteger('items_of_storeins_id');
            $table->decimal('stock_quantity',10,2);
            $table->decimal('req_quantity',10,2);
            $table->text('last_purchase-from');
            $table->decimal('purchase_rate',10,2);
            $table->text('remarks');
            $table->enum('status',['Pending','Complete'])->default('Pending');

            $table->timestamps();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('items_of_storeins_id')->references('id')->on('items_of_storeins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
