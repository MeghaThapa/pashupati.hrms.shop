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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('do_no');
            $table->date('do_date');
            $table->unsignedBigInteger('supplier_id');
            $table->decimal('overdue_amount', 10, 2);
            $table->decimal('total_due', 10, 2);
            $table->decimal('party_limit', 10, 2);
            $table->unsignedBigInteger('delivery_order_for_item_id');
            $table->decimal('qty_in_mt', 10, 2)->nullable();
            $table->unsignedBigInteger('bundel_pcs')->nullable();
            $table->decimal('base_rate_per_kg', 10, 2);
            $table->text('collection')->nullable();
            $table->text('pending_sauda')->nullable();
            $table->enum('status',['Pending','Approved','Cancelled','Approved & Delivered'])->default('Pending');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('created_by')->references('id')->on('users');
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
        Schema::dropIfExists('delivery_orders');
    }
};
